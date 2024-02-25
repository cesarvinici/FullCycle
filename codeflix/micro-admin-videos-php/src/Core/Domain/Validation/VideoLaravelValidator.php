<?php

namespace Core\Domain\Validation;

use Core\Domain\Entity\Entity;
use Core\Domain\Validation\ValidatorInterface;
use Illuminate\Support\Facades\Validator;

class VideoLaravelValidator implements ValidatorInterface
{

    public function validate(Entity $entity): void
    {
        $data = $this->partEntity($entity);

        $validator = Validator::make($data, [
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:3|max:255',
            'yearLaunched' => 'required|integer',
            'duration' => 'required|integer',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->messages() as $error) {
                $entity->notification->addError(['context' => 'video', 'message' => $error[0]]);
            }
        }
    }

    private function partEntity(Entity $entity): array
    {
        return [
            'title' => $entity->title,
            'description' => $entity->description,
            'yearLaunched' => $entity->yearLaunched,
            'duration' => $entity->duration,
        ];
    }
}
