<?php

namespace App\Traits;

trait DatabaseCompatibility
{
    /**
     * Get the database driver being used
     */
    public function getDatabaseDriver(): string
    {
        return config('database.default');
    }

    /**
     * Check if we're using SQLite
     */
    public function isUsingSQLite(): bool
    {
        return $this->getDatabaseDriver() === 'sqlite';
    }

    /**
     * Check if we're using MySQL
     */
    public function isUsingMySQL(): bool
    {
        return $this->getDatabaseDriver() === 'mysql';
    }

    /**
     * Check if we're using PostgreSQL
     */
    public function isUsingPostgreSQL(): bool
    {
        return $this->getDatabaseDriver() === 'pgsql';
    }

    /**
     * Get database-specific query for status validation
     */
    public function getStatusValidationQuery(string $status): string
    {
        $validStatuses = ['active', 'pending', 'hidden', 'deleted'];

        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException("Invalid status: {$status}");
        }

        if ($this->isUsingSQLite()) {
            return "status = \"{$status}\"";
        } else {
            return "status = '{$status}'";
        }
    }

    /**
     * Get database-specific enum values
     */
    public function getStatusEnumValues(): array
    {
        return ['active', 'pending', 'hidden', 'deleted'];
    }

    /**
     * Validate status value for database compatibility
     */
    public function validateStatus(string $status): bool
    {
        return in_array($status, $this->getStatusEnumValues());
    }

    /**
     * Get database-specific boolean value
     */
    public function getBooleanValue(bool $value): mixed
    {
        if ($this->isUsingSQLite()) {
            return $value ? 1 : 0;
        } else {
            return $value;
        }
    }

    /**
     * Get database-specific timestamp format
     */
    public function getTimestampFormat(): string
    {
        if ($this->isUsingSQLite()) {
            return 'Y-m-d H:i:s';
        } else {
            return 'Y-m-d H:i:s';
        }
    }
}
