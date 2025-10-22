<?php

namespace App\Models;

use Core\Model;

class TimeSlot extends Model
{
    protected $table = 'time_slots';
    protected $primaryKey = 'id';
    protected $timestamps = true;

    /**
     * Aktif saat dilimlerini getir
     */
    public function getActive()
    {
        $query = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY start_time ASC";
        return $this->query($query);
    }

    /**
     * Saat dilimini format edilmiş string olarak getir
     */
    public function getFormattedTimeSlot($timeSlot)
    {
        $start = date('H:i', strtotime($timeSlot['start_time']));
        $end = date('H:i', strtotime($timeSlot['end_time']));
        return "{$start} - {$end}";
    }

    /**
     * Yeni saat dilimi ekle
     */
    public function addTimeSlot($timeStart, $timeEnd, $duration = 30)
    {
        // Önce aynı zaman diliminin olup olmadığını kontrol et
        $existing = $this->query("SELECT id FROM {$this->table} WHERE start_time = ? AND end_time = ?", [$timeStart, $timeEnd]);
        
        if (!empty($existing)) {
            // Mevcut kayıt varsa, is_active'i 1 yap ve güncelle
            $existingId = $existing[0]['id'];
            return $this->update($existingId, [
                'duration' => $duration,
                'is_active' => 1
            ]);
        } else {
            // Yeni kayıt oluştur
            return $this->create([
                'start_time' => $timeStart,
                'end_time' => $timeEnd,
                'duration' => $duration,
                'is_active' => 1
            ]);
        }
    }

    /**
     * Saat dilimini güncelle
     */
    public function updateTimeSlot($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Saat dilimini sil (soft delete - is_active = 0)
     */
    public function deleteTimeSlot($id)
    {
        return $this->update($id, ['is_active' => 0]);
    }

    /**
     * Belirli bir süre için saat dilimlerini otomatik oluştur
     */
    public function generateTimeSlots($startTime, $endTime, $intervalMinutes = 30)
    {
        $start = strtotime($startTime);
        $end = strtotime($endTime);
        $interval = $intervalMinutes * 60; // saniyeye çevir

        $slots = [];
        $current = $start;

        while ($current < $end) {
            $slotStart = date('H:i:s', $current);
            $slotEnd = date('H:i:s', $current + $interval);
            
            // Bitiş saatini kontrol et
            if ($current + $interval > $end) {
                break;
            }

            $slots[] = [
                'start_time' => $slotStart,
                'end_time' => $slotEnd,
                'duration' => $intervalMinutes
            ];

            $current += $interval;
        }

        return $slots;
    }

    /**
     * Çakışan saat dilimlerini kontrol et
     */
    public function checkConflict($timeStart, $timeEnd, $excludeId = null)
    {
        $query = "SELECT * FROM {$this->table} WHERE is_active = 1 AND (
            (start_time <= ? AND end_time > ?) OR 
            (start_time < ? AND end_time >= ?) OR
            (start_time >= ? AND end_time <= ?)
        )";
        
        $params = [$timeStart, $timeStart, $timeEnd, $timeEnd, $timeStart, $timeEnd];
        
        if ($excludeId) {
            $query .= " AND id != ?";
            $params[] = $excludeId;
        }

        $conflicts = $this->query($query, $params);
        return !empty($conflicts);
    }
}