<?php
/**
 * Activity Area Model
 * Vildan Portal - Okul Yönetim Sistemi
 */

namespace App\Models;

use Core\Model;

class ActivityArea extends Model
{
    public function __construct() {
        parent::__construct();
    }

    protected $table = 'activity_areas';
    protected $primaryKey = 'id';
    protected $timestamps = true;
    
    /**
     * Aktif etkinlik alanlarını getir
     * 
     * @return array
     */
    public function getActive()
    {
        return $this->where(['is_active' => 1], [
            'order' => ['area_name' => 'ASC']
        ]);
    }    /**
     * API için liste (ID ve isim)
     */
    public function getForSelect() {
        $sql = "SELECT id, area_name, color_code, default_slot_duration
                FROM {$this->table}
                WHERE is_active = 1
                ORDER BY sort_order ASC, area_name ASC";
        
        $this->getDb()->query($sql);
        return $this->getDb()->resultSet();
    }
}

