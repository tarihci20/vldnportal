<?php
/**
 * Etüt Form Settings Model
 * Vildan Portal - Okul Yönetim Sistemi
 */

namespace App\Models;

use Core\Model;

class EtutFormSettings extends Model
{
    protected $table = 'etut_form_settings';
    protected $primaryKey = 'id';
    
    /**
     * Form tipi için ayarları getir
     */
    public function getByFormType($formType)
    {
        // Charset ayarı
        $this->getDb()->query("SET NAMES utf8mb4");
        $this->getDb()->execute();
        
        $sql = "SELECT * FROM {$this->table} WHERE form_type = :form_type LIMIT 1";
        $this->getDb()->query($sql);
        $this->getDb()->bind(':form_type', $formType);
        return $this->getDb()->single();
    }
    
    /**
     * Form açık mı kontrol et
     */
    public function isFormActive($formType)
    {
        $settings = $this->getByFormType($formType);
        return $settings && $settings['is_active'] == 1;
    }
    
    /**
     * Form durumunu değiştir (aç/kapat)
     */
    public function toggleFormStatus($formType)
    {
        $sql = "UPDATE {$this->table} SET is_active = NOT is_active WHERE form_type = :form_type";
        $this->getDb()->query($sql);
        $this->getDb()->bind(':form_type', $formType);
        return $this->getDb()->execute();
    }
    
    /**
     * Form ayarlarını güncelle
     */
    public function updateSettings($formType, $data)
    {
        // Note: max_applications_per_student removed — unlimited applications allowed
        $sql = "UPDATE {$this->table} 
                SET is_active = :is_active,
                    title = :title,
                    description = :description,
                    closed_message = :closed_message
                WHERE form_type = :form_type";
        
        $this->getDb()->query($sql);
        $this->getDb()->bind(':form_type', $formType);
        $this->getDb()->bind(':is_active', $data['is_active'] ?? 1);
        $this->getDb()->bind(':title', $data['title'] ?? '');
        $this->getDb()->bind(':description', $data['description'] ?? '');
        $this->getDb()->bind(':closed_message', $data['closed_message'] ?? '');
    // no binding for max_applications (removed)
        
        return $this->getDb()->execute();
    }
    
    /**
     * Tüm form ayarlarını getir (array olarak)
     */
    public function getAllSettings()
    {
        // Charset ayarı
        $this->getDb()->query("SET NAMES utf8mb4");
        $this->getDb()->execute();
        
        $sql = "SELECT * FROM {$this->table} ORDER BY form_type";
        $this->getDb()->query($sql);
        $results = $this->getDb()->resultSet();
        
        // form_type key'li array'e çevir
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['form_type']] = $row;
        }
        
        return $settings;
    }
    
    /**
     * Öğrenci başvuru sayısını kontrol et
     */
    public function canStudentApply($tcNo, $formType)
    {
        // Allow applications as long as the form is active. We no longer limit by student.
        $settings = $this->getByFormType($formType);
        return $settings && $settings['is_active'] == 1;
    }
}
