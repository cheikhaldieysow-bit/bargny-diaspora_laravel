<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final class ProjectSearchServices
{

    // TODO: Move these to config or Enum for production
    // Config provides flexibility, Enum provides type safety
    private const DEFAULT_PER_PAGE = 15;
    private const DEFAULT_SORT = 'created_at';
    private const DEFAULT_ORDER = 'desc';
    private const ALLOWED_SORTS = ['created_at', 'updated_at', 'budget', 'duration', 'titre'];

    public function search(callable $ownerRelationFactory, array $filters): array
    {
        $query = $ownerRelationFactory()->withCount('documents');

        $this->applyFilters($query, $filters);

        return [
            'paginator' => $this->paginate($query, $filters),
            'facets' => $this->computeFacets($query, $filters),
        ];
    }

    private function applyFilters(Builder $query, array $filters): void
    {
        if ($search = $filters['q'] ?? null) {
            $this->applySearch($query, $search);
        }

        if ($status = $filters['status'] ?? null) {
            $query->whereIn('status', (array) $status);
        }

        if (isset($filters['funded'])) {
            $filters['funded']
                ? $query->whereNotNull('funded_at')
                : $query->whereNull('funded_at');
        }

        $this->applyRange($query, 'budget', $filters['min_budget'] ?? null, $filters['max_budget'] ?? null);
        $this->applyRange($query, 'duration', $filters['duration_min'] ?? null, $filters['duration_max'] ?? null);
        $this->applyDateRange($query, $filters['created_from'] ?? null, $filters['created_to'] ?? null);
    }

    private function applySearch(Builder $query, string $search): void
    {
        $term = '%' . trim($search) . '%';

        $query->where(fn($q) => $q
            ->where('titre', 'like', $term)
            ->orWhere('description', 'like', $term)
            ->orWhere('problem', 'like', $term)
        );
    }

    private function applyRange(Builder $query, string $column, ?float $min, ?float $max): void
    {
        if ($min !== null) {
            $query->where($column, '>=', $min);
        }

        if ($max !== null) {
            $query->where($column, '<=', $max);
        }
    }

    private function applyDateRange(Builder $query, ?string $from, ?string $to): void
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }
    }

    private function computeFacets(Builder $query, array $filters): array
    {
        if (empty($filters['facets'])) {
            return [];
        }

        $baseQuery = clone $query;

        return [
            'status' => $this->statusFacets($baseQuery),
            'funded' => $this->fundedFacets($baseQuery),
        ];
    }

    private function statusFacets(Builder $query): array
    {
        return $query
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }

    private function fundedFacets(Builder $query): array
    {
        return $query
            ->select(DB::raw('funded_at IS NOT NULL as is_funded, count(*) as total'))
            ->groupBy('is_funded')
            ->get()
            ->mapWithKeys(fn($row) => [$row->is_funded ? 'funded' : 'unfunded' => (int) $row->total])
            ->toArray();
    }

    private function paginate(Builder $query, array $filters): LengthAwarePaginator
    {
        $sort = in_array($filters['sort'] ?? null, self::ALLOWED_SORTS, true)
            ? $filters['sort']
            : self::DEFAULT_SORT;

        return $query
            ->orderBy($sort, $filters['order'] ?? self::DEFAULT_ORDER)
            ->paginate($filters['per_page'] ?? self::DEFAULT_PER_PAGE)
            ->appends(request()->query());
    }
}
