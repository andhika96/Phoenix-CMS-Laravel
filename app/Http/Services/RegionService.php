<?php

namespace App\Services;

use App\Contracts\IRegionService;
use App\Enums\QueryAcceptedComparatorEnum;
use App\Enums\RegionModelEnum;
use App\Models\RegDistrict;
use App\Models\RegProvince;
use App\Models\RegRegency;
use App\Models\RegVillage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RegionService implements IRegionService
{
    public function find($keyword, $limit = 15): Collection
    {
        $regions = RegRegency::select(
            'reg_regencies.id as regency_id',
            'reg_regencies.name as regency_name',
            'reg_provinces.name as province_name',
        )
            ->leftJoin('reg_provinces', 'reg_regencies.province_id', '=', 'reg_provinces.id')
            ->leftJoin('reg_districts', 'reg_regencies.id', '=', 'reg_districts.regency_id')
            ->leftJoin('reg_villages', 'reg_districts.id', '=', 'reg_villages.district_id')
            ->where(function ($query) use ($keyword) {
                $query->where('reg_regencies.name', 'like', '%' . $keyword . '%')
                    ->orWhere('reg_provinces.name', 'like', '%' . $keyword . '%');
            })
            ->distinct();
        // ->where('regency_name', 'like', '%' . $keyword . '%')

        $regions = $limit == -1 ? $regions->get() : $regions->limit($limit)->get();

        return $regions;
    }

    public function findLocationByIndexes(
        array $indexes,
        bool $any,
        int $limit,
        RegionModelEnum $regionModel,
        QueryAcceptedComparatorEnum $comparator
    ) {
        $regionModel = $this->callRegionModel($regionModel);
        $table = $regionModel->getTable();
        $columns = Schema::getColumnListing($table);
        $query = $regionModel::query();

        foreach ($indexes as $column => $value) {
            if ($comparator == QueryAcceptedComparatorEnum::LIKE) {
                $value = "%{$value}%";
            }

            if (in_array($column, $columns)) {
                $any
                    ? $query->orWhere($column, $comparator->value, $value)
                    : $query->where($column, $comparator->value, $value);
            }
        }

        if ($limit == -1) {
            $results = $query->get();
        } else {
            $perPage = $limit;
            $currentPage = request()->get('page', 1);
            $results = $query->paginate($perPage, ['*'], 'page', $currentPage);
        }

        return $results;
    }

    protected function callRegionModel(RegionModelEnum $regionModel)
    {
        switch ($regionModel) {
            case RegionModelEnum::PROVINCE:
                $model = new RegProvince();
                break;
            case RegionModelEnum::CITY:
                $model = new RegRegency();
                break;
            case RegionModelEnum::DISTRICT:
                $model = new RegDistrict();
                break;
            case RegionModelEnum::VILLAGE:
                $model = new RegVillage();
                break;

            default:
                throw new \InvalidArgumentException("Invalid region model");
        }

        return $model;
    }
}
