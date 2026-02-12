<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;

class ProjectSearchServices
{
    protected DatabaseManager $db;

    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    /**
     * @param callable $ownerRelationFactory
     * @param array $filters
     * @return array{paginator: LengthAwarePaginator, facets: array}
     */
    public function search(callable $ownerRelationFactory, array $filters): array
    {
        $perPage = $filters['per_page'] ?? 15;
        $sort = $filters['sort'] ?? 'created_at';
        $order = $filters['order'] ?? 'desc';

        $query = $ownerRelationFactory()->withCount('documents');

        if (!empty($filters['q'])) {
            $q = trim($filters['q']);

            // Use LIKE search (FULLTEXT index not yet created)
            // To enable full-text: ALTER TABLE projects ADD FULLTEXT INDEX ft_search (titre, description, problem);
            $query->where(function ($qb) use ($q) {
                $qb->where('titre', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%")
                   ->orWhere('problem', 'like', "%{$q}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->whereIn('status', (array) $filters['status']);
        }

        if (isset($filters['funded'])) {
            $filters['funded'] ? $query->whereNotNull('funded_at') : $query->whereNull('funded_at');
        }

        if (isset($filters['min_budget'])) {
            $query->where('budget', '>=', $filters['min_budget']);
        }
        if (isset($filters['max_budget'])) {
            $query->where('budget', '<=', $filters['max_budget']);
        }

        if (isset($filters['duration_min'])) {
            $query->where('duration', '>=', $filters['duration_min']);
        }
        if (isset($filters['duration_max'])) {
            $query->where('duration', '<=', $filters['duration_max']);
        }

        if (!empty($filters['created_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_from']);
        }
        if (!empty($filters['created_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_to']);
        }

        $allowedSorts = ['created_at', 'updated_at', 'budget', 'duration', 'titre'];
        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        $baseQuery = clone $query;
        $facets = [];

        if (!empty($filters['facets'])) {
            $statusCounts = (clone $baseQuery)
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->toArray();

            $fundedCounts = (clone $baseQuery)
                ->select(DB::raw('CASE WHEN funded_at IS NULL THEN 0 ELSE 1 END as funded_flag'), DB::raw('count(*) as total'))
                ->groupBy('funded_flag')
                ->get()
                ->mapWithKeys(function ($row) {
                    return [$row->funded_flag ? 'funded' : 'unfunded' => (int) $row->total];
                })->toArray();

            $facets = [
                'status' => $statusCounts,
                'funded' => $fundedCounts,
            ];
        }

        $paginator = $query->orderBy($sort, $order)
            ->paginate($perPage)
            ->appends(request()->query());

        return [
            'paginator' => $paginator,
            'facets' => $facets,
        ];
    }

    protected function toBooleanMode(string $q): string
    {
        $tokens = preg_split('/\s+/', $q);
        $tokens = array_filter(array_map(function ($t) {
            $t = trim($t);
            $t = str_replace(['+', '-', '<', '>', '@', '(', ')', '~', '*', '"'], ' ', $t);
            return $t;
        }, $tokens));
        $tokens = array_slice($tokens, 0, 10);
        $tokens = array_map(fn($t) => $t . '*', $tokens);
        return implode(' ', $tokens);
    }
}
