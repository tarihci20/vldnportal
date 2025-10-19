<?php

namespace App\Models;

use Core\Model;

class ActivityAreaTimeSlot extends Model
{
    protected $table = 'activity_area_time_slots';
    protected $primaryKey = 'id';
    protected $timestamps = true;

    /**
     * Belirli alan için saat dilimi ayarlarını getir
     */
    public function getByAreaId($areaId)
    {
        $query = "SELECT * FROM {$this->table} WHERE area_id = ? AND is_active = 1 LIMIT 1";
        $results = $this->query($query, [$areaId]);
        return $results[0] ?? null;
    }

    /**
     * Tüm alan saat dilimi ayarlarını getir
     */
    public function getAllWithAreaNames()
    {
        $query = "
            SELECT 
                aats.*,
                aa.area_name,
                aa.color_code
            FROM {$this->table} aats
            JOIN activity_areas aa ON aa.id = aats.area_id
            WHERE aats.is_active = 1
            ORDER BY aa.area_name ASC
        ";
        
        return $this->query($query);
    }

    /**
     * Alan için saat dilimi ayarı ekle/güncelle
     */
    public function setAreaTimeSlot($areaId, $durationMinutes)
    {
        // Mevcut ayarı kontrol et
        $existing = $this->getByAreaId($areaId);
        
        if ($existing) {
            // Güncelle
            return $this->update($existing['id'], [
                'duration_minutes' => $durationMinutes
            ]);
        } else {
            // Yeni ekle
            return $this->create([
                'area_id' => $areaId,
                'duration_minutes' => $durationMinutes,
                'is_active' => 1
            ]);
        }
    }

    /**
     * Alan saat dilimi ayarını sil
     */
    public function deleteAreaTimeSlot($areaId)
    {
        $query = "UPDATE {$this->table} SET is_active = 0 WHERE area_id = ?";
        return $this->query($query, [$areaId]);
    }

    /**
     * Belirli alan için süre dilimini getir (varsayılan 30 dk)
     */
    public function getAreaDuration($areaId)
    {
        $setting = $this->getByAreaId($areaId);
        return $setting ? $setting['duration_minutes'] : 30;
    }

    /**
     * Mevcut süre seçenekleri
     */
    public function getDurationOptions()
    {
        return [
            20 => '20 Dakika',
            30 => '30 Dakika', 
            45 => '45 Dakika',
            60 => '60 Dakika'
        ];
    }

    /**
     * Alan için mevcut saat dilimlerini güncelle
     */
    public function generateSlotsForArea($areaId, $startTime = '08:00', $endTime = '22:00')
    {
        $duration = $this->getAreaDuration($areaId);
        
        // TimeSlot model'ini kullan
        $timeSlotModel = new TimeSlot();
        return $timeSlotModel->generateTimeSlots($startTime, $endTime, $duration);
    }
}