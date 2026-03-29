<?php

declare(strict_types=1);
namespace App\Models\DTO;

use InvalidArgumentException;

readonly class DeleteUserDTO {
    private function __construct (
        public int $id
    ) {}

	public static function parse (array $data = []) : self {
        $id	= $data["id"] ?? 0;

        return new self($id);
    }
}