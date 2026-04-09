<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait SnakeCaseValidatedDataTrait
{
    public function validated($default = false, $nested = true): array
    {
        $validatedData = parent::validated();

        if ($default) {
            return $validatedData;
        }

        return $this->transformToSnakeCase($validatedData, $nested);
    }

    private function transformToSnakeCase(array $data, $nested = true): array
    {
        $snakeCaseData = [];

        foreach ($data as $key => $value) {
            $newKey = Str::snake($key);

            if (is_array($value) && $nested) {
                $snakeCaseData[$newKey] = $this->transformToSnakeCase($value, $nested);
            } else {
                $snakeCaseData[$newKey] = $value;
            }
        }

        return $snakeCaseData;
    }
}
