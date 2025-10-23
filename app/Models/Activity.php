<?php
/**
 * Activity Model
 * Vildan Portal - Okul Yönetim Sistemi
 */

namespace App\Models;

use Core\Model;

class Activity extends Model
{
    public function __construct() {
        parent::__construct();
    }

    protected $table = 'vp_activities';
    protected $primaryKey = 'id';
    protected $timestamps = true;
    
    /**
     * Detaylı etkinlik listesi getir (JOIN ile)
     */
    public function getActivitiesWithDetails($filters = [], $limit = 20, $offset = 0)
    {
        $sql = "SELECT 
                    a.*,
                    aa.area_name,
                    aa.color_code as area_color,
                    u.full_name as created_by_name
                FROM vp_activities a
                LEFT JOIN vp_activity_areas aa ON a.area_id = aa.id  
                LEFT JOIN vp_users u ON a.created_by = u.id
                WHERE 1=1";
        
        $params = [];
        
        // Filtreler
        if (!empty($filters['area_id'])) {
            $sql .= " AND a.area_id = :area_id";
            $params['area_id'] = $filters['area_id'];
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND a.activity_date >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND a.activity_date <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        // Sıralama
        $sql .= " ORDER BY a.activity_date DESC, a.start_time DESC";
        
        // Sayfalama
        $sql .= " LIMIT :limit OFFSET :offset";
        $params['limit'] = $limit;
        $params['offset'] = $offset;
        
        return $this->query($sql, $params);
    }
    
    /**
     * Etkinlik sayısını getir (filtrelere göre)
     */
    public function countActivities($filters = [])
    {
        $sql = "SELECT COUNT(*) as total 
                FROM vp_activities a
                WHERE 1=1";
        
        $params = [];
        
        // Filtreler
        if (!empty($filters['area_id'])) {
            $sql .= " AND a.area_id = :area_id";
            $params['area_id'] = $filters['area_id'];
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND a.activity_date >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND a.activity_date <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        $result = $this->query($sql, $params);
        return $result[0]['total'] ?? 0;
    }
    
    /**
     * Çakışma kontrolü
     */
    public function checkConflicts($areaId, $startDatetime, $endDatetime, $excludeId = null)
    {
        $sql = "SELECT 
                    a.*,
                    aa.area_name
                FROM vp_activities a
                LEFT JOIN vp_activity_areas aa ON a.area_id = aa.id
                WHERE a.area_id = :area_id
                AND a.activity_date = DATE(:start_dt)
                AND (
                    (a.start_time < TIME(:end_dt) AND a.end_time > TIME(:start_dt))
                )";
        
        $params = [
            'area_id' => $areaId,
            'start_dt' => $startDatetime,
            'end_dt' => $endDatetime
        ];
        
        if ($excludeId) {
            $sql .= " AND a.id != :exclude_id";
            $params['exclude_id'] = $excludeId;
        }
        
        return $this->query($sql, $params);
    }
    
    /**
     * Bugünkü etkinlikler
     */
    public function getToday() 
    {
        $today = date('Y-m-d');
        
        $sql = "SELECT a.*, aa.area_name, aa.color_code
                FROM vp_activities a
                LEFT JOIN vp_activity_areas aa ON a.area_id = aa.id
                WHERE a.activity_date = :today
                ORDER BY a.start_time ASC";
        
        return $this->query($sql, ['today' => $today]);
    }
    
    /**
     * Belirli tarih ve alan için rezerve edilen saat dilimlerini getir
     */
    public function getReservedTimeSlots($areaId, $date)
    {
        $sql = "SELECT start_time, end_time, activity_name
                FROM vp_activities 
                WHERE area_id = :area_id 
                AND activity_date = :date
                ORDER BY start_time ASC";
        
        return $this->query($sql, [
            'area_id' => $areaId,
            'date' => $date
        ]);
    }
    
    /**
     * Haftalık etkinlik istatistikleri
     */
    public function getWeeklyStats()
    {
        $sql = "SELECT 
                    DATE(activity_date) as date,
                    COUNT(*) as count,
                    DAYNAME(activity_date) as day_name
                FROM vp_activities 
                WHERE activity_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                AND activity_date <= CURDATE()
                GROUP BY DATE(activity_date)
                ORDER BY activity_date ASC";
        
        return $this->query($sql);
    }
    
    /**
     * Takvim için etkinlikleri getir
     */
    public function getForCalendar($startDate, $endDate)
    {
        $sql = "SELECT 
                    a.*,
                    aa.area_name,
                    aa.color_code
                FROM vp_activities a
                LEFT JOIN vp_activity_areas aa ON a.area_id = aa.id
                WHERE a.activity_date BETWEEN :start_date AND :end_date
                ORDER BY a.activity_date ASC, a.start_time ASC";
        
        return $this->query($sql, [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    /**
     * Get activities by array of IDs
     * @param array $ids
     * @return array
     */
    public function getByIds(array $ids)
    {
        if (empty($ids)) return [];
        $ids = array_map('intval', $ids);
        $placeholders = implode(',', $ids);
        $sql = "SELECT a.*, aa.area_name, aa.color_code as area_color, u.full_name as created_by_name FROM vp_activities a LEFT JOIN vp_activity_areas aa ON a.area_id = aa.id LEFT JOIN vp_users u ON a.created_by = u.id WHERE a.id IN ({$placeholders}) ORDER BY a.activity_date DESC";
        return $this->query($sql);
    }

    /**
     * Get activities by a specific date
     * @param string $date YYYY-MM-DD
     * @return array
     */
    public function getByDate($date)
    {
        $sql = "SELECT a.*, aa.area_name, aa.color_code as area_color, u.full_name as created_by_name FROM vp_activities a LEFT JOIN vp_activity_areas aa ON a.area_id = aa.id LEFT JOIN vp_users u ON a.created_by = u.id WHERE a.activity_date = :d ORDER BY a.start_time ASC";
        return $this->query($sql, ['d' => $date]);
    }
    
    /**
     * Çakışma kontrolü - Yeni Rezervasyon Sistemi
     * @param int $areaId Alan ID
     * @param string $date Tarih (Y-m-d)
     * @param string $time Saat (H:i)
     * @return bool
     */
    public function cakismaKontrol($areaId, $date, $time)
    {
        $sql = "SELECT COUNT(*) as count
                FROM vp_activities 
                WHERE area_id = :area_id 
                AND activity_date = :date 
                AND start_time = :time";
        
        $result = $this->query($sql, [
            'area_id' => $areaId,
            'date' => $date,
            'time' => $time
        ]);
        
        return ($result[0]['count'] ?? 0) > 0;
    }
    
    /**
     * Çoklu çakışma kontrolü (tekrar kayıtları için)
     * @param int $areaId
     * @param array $tarihSaatListesi [['tarih' => 'Y-m-d', 'saat' => 'H:i'], ...]
     * @return array Çakışan kayıtlar
     */
    public function cokluCakismaKontrol($areaId, $tarihSaatListesi)
    {
        $cakisanlar = [];
        
        foreach ($tarihSaatListesi as $item) {
            if ($this->cakismaKontrol($areaId, $item['tarih'], $item['saat'])) {
                $cakisanlar[] = [
                    'tarih' => $item['tarih'],
                    'saat' => $item['saat'],
                    'tarih_formatted' => date('d.m.Y', strtotime($item['tarih']))
                ];
            }
        }
        
        return $cakisanlar;
    }
    
    /**
     * Tarih dizisi oluştur - Tekrar türüne göre
     * @param string $baslangicTarihi
     * @param string $tekrarTuru
     * @param array $ekstraParametreler
     * @return array
     */
    public function tarihDizisiOlustur($baslangicTarihi, $tekrarTuru, $ekstraParametreler = [])
    {
        $tarihler = [];
        $baslangic = new DateTime($baslangicTarihi);
        
        switch ($tekrarTuru) {
            case 'gunluk_3':
                for ($i = 1; $i <= 3; $i++) {
                    $yeniTarih = clone $baslangic;
                    $yeniTarih->add(new DateInterval('P' . $i . 'D'));
                    $tarihler[] = $yeniTarih->format('Y-m-d');
                }
                break;
                
            case 'gunluk_7':
                for ($i = 1; $i <= 7; $i++) {
                    $yeniTarih = clone $baslangic;
                    $yeniTarih->add(new DateInterval('P' . $i . 'D'));
                    $tarihler[] = $yeniTarih->format('Y-m-d');
                }
                break;
                
            case 'haftalik_3':
                for ($i = 1; $i <= 3; $i++) {
                    $yeniTarih = clone $baslangic;
                    $yeniTarih->add(new DateInterval('P' . ($i * 7) . 'D'));
                    $tarihler[] = $yeniTarih->format('Y-m-d');
                }
                break;
                
            case 'belirli_gunler':
                $secilenGunler = $ekstraParametreler['gunler'] ?? [];
                $haftaSayisi = $ekstraParametreler['hafta_sayisi'] ?? 4;
                
                for ($hafta = 0; $hafta < $haftaSayisi; $hafta++) {
                    foreach ($secilenGunler as $gun) {
                        $yeniTarih = clone $baslangic;
                        $yeniTarih->add(new DateInterval('P' . ($hafta * 7) . 'D'));
                        
                        // Haftanın belirli gününe ayarla
                        $gunFarki = $gun - $yeniTarih->format('N');
                        if ($gunFarki != 0) {
                            $yeniTarih->add(new DateInterval('P' . $gunFarki . 'D'));
                        }
                        
                        if ($yeniTarih > $baslangic) {
                            $tarihler[] = $yeniTarih->format('Y-m-d');
                        }
                    }
                }
                break;
                
            case 'ay_sonu':
                $aySonu = new DateTime($baslangicTarihi);
                $aySonu->modify('last day of this month');
                
                $aktuelTarih = clone $baslangic;
                $aktuelTarih->add(new DateInterval('P1D'));
                
                while ($aktuelTarih <= $aySonu) {
                    $tarihler[] = $aktuelTarih->format('Y-m-d');
                    $aktuelTarih->add(new DateInterval('P1D'));
                }
                break;
                
            case 'tarih_araligi':
                $bitisTarihi = new DateTime($ekstraParametreler['bitis_tarihi']);
                $aktuelTarih = clone $baslangic;
                $aktuelTarih->add(new DateInterval('P1D'));
                
                while ($aktuelTarih <= $bitisTarihi) {
                    $tarihler[] = $aktuelTarih->format('Y-m-d');
                    $aktuelTarih->add(new DateInterval('P1D'));
                }
                break;
        }
        
        return $tarihler;
    }
    
    /**
     * Tekrarlı rezervasyon oluştur
     * @param array $anaVeri Ana kayıt verisi
     * @param array $tarihListesi Tekrar edilecek tarihler
     * @return array Sonuç raporu
     */
    public function tekrarliRezervasyonOlustur($anaVeri, $tarihListesi)
    {
        $rapor = [
            'ana_kayit_id' => null,
            'basarili_kayitlar' => [],
            'basarisiz_kayitlar' => [],
            'toplam_basarili' => 0,
            'toplam_basarisiz' => 0
        ];
        
        try {
            // Transaction başlat
            $this->getDb()->beginTransaction();
            
            // 1. Ana kaydı oluştur
            $anaVeri['tekrar_durumu'] = 'evet';
            $anaKayitId = $this->create($anaVeri);
            
            if (!$anaKayitId) {
                throw new Exception('Ana kayıt oluşturulamadı');
            }
            
            $rapor['ana_kayit_id'] = $anaKayitId;
            
            // 2. Her tarih için tekrar kaydı oluştur
            foreach ($tarihListesi as $tarih) {
                $cakismaVar = $this->cakismaKontrol(
                    $anaVeri['area_id'], 
                    $tarih, 
                    $anaVeri['start_time']
                );
                
                if (!$cakismaVar) {
                    // Tekrar kaydı oluştur
                    $tekrarVeri = $anaVeri;
                    $tekrarVeri['activity_date'] = $tarih;
                    $tekrarVeri['ana_etkinlik_id'] = $anaKayitId;
                    $tekrarVeri['tekrar_durumu'] = 'evet';
                    
                    $tekrarKayitId = $this->create($tekrarVeri);
                    
                    if ($tekrarKayitId) {
                        $rapor['basarili_kayitlar'][] = [
                            'tarih' => $tarih,
                            'tarih_formatted' => date('d.m.Y', strtotime($tarih)),
                            'id' => $tekrarKayitId
                        ];
                    } else {
                        $rapor['basarisiz_kayitlar'][] = [
                            'tarih' => $tarih,
                            'tarih_formatted' => date('d.m.Y', strtotime($tarih)),
                            'sebep' => 'Kayıt oluşturulamadı'
                        ];
                    }
                } else {
                    $rapor['basarisiz_kayitlar'][] = [
                        'tarih' => $tarih,
                        'tarih_formatted' => date('d.m.Y', strtotime($tarih)),
                        'sebep' => 'Çakışma var'
                    ];
                }
            }
            
            $rapor['toplam_basarili'] = count($rapor['basarili_kayitlar']);
            $rapor['toplam_basarisiz'] = count($rapor['basarisiz_kayitlar']);
            
            // Transaction commit
            $this->getDb()->commit();
            
        } catch (Exception $e) {
            $this->getDb()->rollback();
            throw $e;
        }
        
        return $rapor;
    }
    
    /**
     * Ana kayıtları listele (tekrarlar hariç)
     * @param array $filters
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getAnaKayitlarWithDetails($filters = [], $limit = 20, $offset = 0)
    {
        $sql = "SELECT 
                    a.*,
                    aa.area_name,
                    aa.color_code as area_color,
                    u.full_name as created_by_name,
                    COUNT(t.id) as tekrar_sayisi
                FROM vp_activities a
                LEFT JOIN vp_activity_areas aa ON a.area_id = aa.id  
                LEFT JOIN vp_users u ON a.created_by = u.id
                LEFT JOIN vp_activities t ON a.id = t.ana_etkinlik_id
                WHERE a.ana_etkinlik_id IS NULL";
        
        $params = [];
        
        // Filtreler
        if (!empty($filters['area_id'])) {
            $sql .= " AND a.area_id = :area_id";
            $params['area_id'] = $filters['area_id'];
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND a.activity_date >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND a.activity_date <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        $sql .= " GROUP BY a.id";
        $sql .= " ORDER BY a.activity_date DESC, a.start_time DESC";
        $sql .= " LIMIT :limit OFFSET :offset";
        
        $params['limit'] = $limit;
        $params['offset'] = $offset;
        
        return $this->query($sql, $params);
    }
    
    /**
     * Ana kayıt sayısını getir
     * @param array $filters
     * @return int
     */
    public function countAnaKayitlar($filters = [])
    {
        $sql = "SELECT COUNT(*) as total 
                FROM vp_activities a
                WHERE a.ana_etkinlik_id IS NULL";
        
        $params = [];
        
        if (!empty($filters['area_id'])) {
            $sql .= " AND a.area_id = :area_id";
            $params['area_id'] = $filters['area_id'];
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND a.activity_date >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND a.activity_date <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        $result = $this->query($sql, $params);
        return $result[0]['total'] ?? 0;
    }
    
    /**
     * Tekrar kayıtlarını sil (ana kayıt ile birlikte)
     * @param int $anaKayitId
     * @return bool
     */
    public function tekrarKayitlariniSil($anaKayitId)
    {
        try {
            $this->getDb()->beginTransaction();
            
            // Önce tekrar kayıtlarını sil
            $sql1 = "DELETE FROM vp_activities WHERE ana_etkinlik_id = :ana_id";
            $this->query($sql1, ['ana_id' => $anaKayitId]);
            
            // Sonra ana kaydı sil
            $sql2 = "DELETE FROM vp_activities WHERE id = :id";
            $this->query($sql2, ['id' => $anaKayitId]);
            
            $this->getDb()->commit();
            return true;
            
        } catch (Exception $e) {
            $this->getDb()->rollback();
            return false;
        }
    }
    
    // ============================
    // SAAT DİLİMİ SİSTEMİ METODLARI
    // ============================
    
    /**
     * Tüm aktif saat dilimlerini getir
     */
    public function getAllTimeSlots()
    {
        return $this->query("
            SELECT * 
            FROM vp_time_slots 
            WHERE is_active = 1 
            ORDER BY sort_order
        ");
    }
    
    /**
     * Belirli bir tarih ve alan için boş saat dilimlerini getir
     */
    public function getAvailableTimeSlots($areaId, $date, $excludeActivityId = null)
    {
        $sql = "
            SELECT ts.*,
                   CASE 
                       WHEN reserved.slot_count > 0 THEN 1 
                       ELSE 0 
                   END as is_reserved
            FROM vp_time_slots ts
            LEFT JOIN (
                SELECT ats.time_slot_id, COUNT(*) as slot_count
                FROM vp_activity_time_slots ats
                INNER JOIN vp_activities a ON ats.activity_id = a.id
                WHERE a.area_id = :area_id 
                AND a.activity_date = :activity_date
                AND a.uses_time_slots = 1
        ";
        
        $params = [
            'area_id' => $areaId,
            'activity_date' => $date
        ];
        
        if ($excludeActivityId) {
            $sql .= " AND a.id != :exclude_id";
            $params['exclude_id'] = $excludeActivityId;
        }
        
        $sql .= "
                GROUP BY ats.time_slot_id
            ) reserved ON ts.id = reserved.time_slot_id
            WHERE ts.is_active = 1
            ORDER BY ts.sort_order
        ";
        
        return $this->query($sql, $params);
    }
    
    /**
     * Sadece boş olan saat dilimlerini getir
     */
    public function getFreeTimeSlots($areaId, $date, $excludeActivityId = null)
    {
        $allSlots = $this->getAvailableTimeSlots($areaId, $date, $excludeActivityId);
        
        return array_filter($allSlots, function($slot) {
            return $slot['is_reserved'] == 0;
        });
    }
    
    /**
     * Belirli saat dilimlerinin çakışma kontrolü
     */
    public function checkTimeSlotsConflict($areaId, $date, $timeSlotIds, $excludeActivityId = null)
    {
        if (empty($timeSlotIds)) {
            return ['has_conflict' => false, 'conflicts' => []];
        }
        
        $placeholders = str_repeat('?,', count($timeSlotIds) - 1) . '?';
        
        $sql = "
            SELECT 
                a.id,
                a.activity_name,
                ts.time_code,
                ts.display_time,
                ats.time_slot_id
            FROM vp_activities a
            INNER JOIN vp_activity_time_slots ats ON a.id = ats.activity_id
            INNER JOIN vp_time_slots ts ON ats.time_slot_id = ts.id
            WHERE a.area_id = ? 
            AND a.activity_date = ?
            AND ats.time_slot_id IN ($placeholders)
            AND a.uses_time_slots = 1
        ";
        
        $params = [$areaId, $date, ...$timeSlotIds];
        
        if ($excludeActivityId) {
            $sql .= " AND a.id != ?";
            $params[] = $excludeActivityId;
        }
        
        $conflicts = $this->query($sql, $params);
        
        return [
            'has_conflict' => !empty($conflicts),
            'conflicts' => $conflicts,
            'conflict_count' => count($conflicts)
        ];
    }
    
    /**
     * Saat dilimlerinin başlangıç ve bitiş saatlerini al
     */
    private function getTimeSlotRange($timeSlotIds)
    {
        if (empty($timeSlotIds)) {
            return [
                'start_time' => '00:00:00',
                'end_time' => '23:59:59'
            ];
        }
        
        // ID'leri güvenli hale getir
        $ids = array_map('intval', $timeSlotIds);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $sql = "
            SELECT 
                MIN(start_time) as start_time,
                MAX(end_time) as end_time
            FROM vp_time_slots
            WHERE id IN ($placeholders)
        ";
        
        $result = $this->query($sql, $ids);
        
        if (!empty($result)) {
            return [
                'start_time' => $result[0]['start_time'] ?? '00:00:00',
                'end_time' => $result[0]['end_time'] ?? '23:59:59'
            ];
        }
        
        return [
            'start_time' => '00:00:00',
            'end_time' => '23:59:59'
        ];
    }
    
    /**
     * Etkinlik oluştur (saat dilimi sistemi ile)
     */
    public function createWithTimeSlots($activityData, $timeSlotIds)
    {
        try {
            $this->getDb()->beginTransaction();
            
            // Önce çakışma kontrolü
            $conflictResult = $this->checkTimeSlotsConflict(
                $activityData['area_id'], 
                $activityData['activity_date'], 
                $timeSlotIds
            );
            
            if ($conflictResult['has_conflict']) {
                throw new \Exception('Seçilen saat dilimlerinde çakışma var!');
            }
            
            // Seçilen saat dilimlerinin başlangıç ve bitiş saatlerini al
            $timeSlotInfo = $this->getTimeSlotRange($timeSlotIds);
            
            // Etkinlik tablosuna ekle
            $activityData['uses_time_slots'] = 1;
            $activityData['start_time'] = $timeSlotInfo['start_time'];
            $activityData['end_time'] = $timeSlotInfo['end_time'];
            $activityData['created_at'] = date('Y-m-d H:i:s');
            
            $activityId = $this->getDb()->insert($this->table, $activityData);
            
            if (!$activityId) {
                throw new \Exception('Etkinlik oluşturulamadı!');
            }
            
            // Saat dilimi ilişkilerini ekle
            foreach ($timeSlotIds as $slotId) {
                $relationData = [
                    'activity_id' => $activityId,
                    'time_slot_id' => $slotId,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $this->getDb()->insert('vp_activity_time_slots', $relationData);
            }
            
            $this->getDb()->commit();
            return $activityId;
            
        } catch (\Exception $e) {
            $this->getDb()->rollback();
            throw $e;
        }
    }
    
    /**
     * Etkinlik güncelle (saat dilimi sistemi ile)
     */
    public function updateWithTimeSlots($activityId, $activityData, $timeSlotIds)
    {
        try {
            $this->getDb()->beginTransaction();
            
            // Çakışma kontrolü (mevcut etkinlik hariç)
            $conflictResult = $this->checkTimeSlotsConflict(
                $activityData['area_id'], 
                $activityData['activity_date'], 
                $timeSlotIds,
                $activityId
            );
            
            if ($conflictResult['has_conflict']) {
                throw new \Exception('Seçilen saat dilimlerinde çakışma var!');
            }
            
            // Etkinlik bilgilerini güncelle
            $this->update($this->table, $activityData, ['id' => $activityId]);
            
            // Mevcut saat dilimi ilişkilerini sil
            $this->query("DELETE FROM vp_activity_time_slots WHERE activity_id = ?", [$activityId]);
            
            // Yeni saat dilimlerini ekle
            foreach ($timeSlotIds as $slotId) {
                $relationData = [
                    'activity_id' => $activityId,
                    'time_slot_id' => $slotId,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $this->getDb()->insert('vp_activity_time_slots', $relationData);
            }
            
            $this->getDb()->commit();
            return true;
            
        } catch (\Exception $e) {
            $this->getDb()->rollback();
            throw $e;
        }
    }
    
    /**
     * Etkinliğin saat dilimlerini getir
     */
    public function getActivityTimeSlots($activityId)
    {
        return $this->query("
            SELECT ts.*, ats.created_at as assigned_at
            FROM vp_time_slots ts
            INNER JOIN vp_activity_time_slots ats ON ts.id = ats.time_slot_id
            WHERE ats.activity_id = ?
            ORDER BY ts.sort_order
        ", [$activityId]);
    }
    
    /**
     * Saat dilimi sistemi ile etkinlik silme
     */
    public function deleteWithTimeSlots($activityId)
    {
        try {
            $this->getDb()->beginTransaction();
            
            // Önce saat dilimi ilişkilerini sil
            $deleted = $this->getDb()->delete('activity_time_slots', ['activity_id' => $activityId]);
            
            // Sonra etkinliği sil
            $deleted = $this->getDb()->delete($this->table, ['id' => $activityId]);
            
            $this->getDb()->commit();
            return $deleted;
            
        } catch (\Exception $e) {
            $this->getDb()->rollback();
            error_log('Activity deleteWithTimeSlots error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Dilim bazlı tekrarlı etkinlik oluşturma
     * ÖNEMLI: Herhangi bir çakışma varsa HİÇBİRİ kaydedilmez!
     */
    public function createRecurringWithTimeSlots($activityData, $timeSlotIds, $dateList)
    {
        try {
            // ÖNCE TÜM TARİHLERİ KONTROL ET - HİÇ BİR ÇAKIŞMA OLMAMALI!
            $conflictingDates = [];
            $conflictDetails = [];
            
            // Seçilen saat dilimlerinin başlangıç ve bitiş saatlerini al
            $timeSlotInfo = $this->getTimeSlotRange($timeSlotIds);
            
            // Her tarih için çakışma kontrolü yap
            foreach ($dateList as $date) {
                $conflictResult = $this->checkTimeSlotsConflict(
                    $activityData['area_id'], 
                    $date, 
                    $timeSlotIds
                );
                
                if ($conflictResult['has_conflict']) {
                    $conflictingDates[] = $date;
                    $conflictDetails[] = [
                        'date' => $date,
                        'conflicting_activities' => $conflictResult['conflicting_activities']
                    ];
                }
            }
            
            // EĞER TEK BİR ÇAKIŞMA BİLE VARSA, HİÇBİRİNİ KAYDETME!
            if (!empty($conflictingDates)) {
                $dateFormatter = function($date) {
                    $dt = new \DateTime($date);
                    $days = ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'];
                    return $dt->format('d.m.Y') . ' ' . $days[$dt->format('w')];
                };
                
                $formattedDates = array_map($dateFormatter, $conflictingDates);
                $dateCount = count($conflictingDates);
                $totalCount = count($dateList);
                
                $errorMessage = sprintf(
                    "Tekrarlı rezervasyon oluşturulamadı! %d/%d tarihte çakışma var: %s (Saat: %s-%s). Lütfen farklı saat dilimleri seçin.",
                    $dateCount,
                    $totalCount,
                    implode(", ", $formattedDates),
                    substr($timeSlotInfo['start_time'], 0, 5),
                    substr($timeSlotInfo['end_time'], 0, 5)
                );
                
                return [
                    'success' => false,
                    'error' => $errorMessage,
                    'conflicting_dates' => $conflictingDates,
                    'conflict_details' => $conflictDetails
                ];
            }
            
            // ÇAKIŞMA YOK, ŞİMDİ KAYDET
            $this->getDb()->beginTransaction();
            
            $createdActivities = [];
            
            // Her tarih için etkinlik oluştur (çakışma olmadığını biliyoruz)
            foreach ($dateList as $date) {
                // Bu tarih için etkinlik oluştur
                $currentActivityData = $activityData;
                $currentActivityData['activity_date'] = $date;
                $currentActivityData['uses_time_slots'] = 1;
                $currentActivityData['start_time'] = $timeSlotInfo['start_time'];
                $currentActivityData['end_time'] = $timeSlotInfo['end_time'];
                $currentActivityData['created_at'] = date('Y-m-d H:i:s');
                
                $activityId = $this->getDb()->insert($this->table, $currentActivityData);
                
                if ($activityId) {
                    // Saat dilimi ilişkilerini ekle
                    foreach ($timeSlotIds as $slotId) {
                        $relationData = [
                            'activity_id' => $activityId,
                            'time_slot_id' => $slotId,
                            'created_at' => date('Y-m-d H:i:s')
                        ];
                        
                        $this->getDb()->insert('vp_activity_time_slots', $relationData);
                    }
                    
                    $createdActivities[] = $activityId;
                }
            }
            
            $this->getDb()->commit();
            
            return [
                'success' => true,
                'created_count' => count($createdActivities),
                'skipped_count' => 0,
                'created_activities' => $createdActivities,
                'skipped_dates' => []
            ];
            
        } catch (\Exception $e) {
            $this->getDb()->rollback();
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Etkinlik listesi saat dilimleri ile birlikte
     */
    public function getActivitiesWithTimeSlots($filters = [], $limit = 20, $offset = 0)
    {
        $sql = "
            SELECT 
                a.*,
                aa.area_name,
                aa.color_code as area_color,
                u.full_name as created_by_name,
                GROUP_CONCAT(
                    ts.display_time 
                    ORDER BY ts.sort_order 
                    SEPARATOR ', '
                ) as time_slots_display,
                COUNT(DISTINCT ats.time_slot_id) as slot_count
            FROM vp_activities a
            LEFT JOIN vp_activity_areas aa ON a.area_id = aa.id
            LEFT JOIN vp_users u ON a.created_by = u.id
            LEFT JOIN vp_activity_time_slots ats ON a.id = ats.activity_id
            LEFT JOIN vp_time_slots ts ON ats.time_slot_id = ts.id
            WHERE a.uses_time_slots = 1
        ";
        
        $params = [];
        
        // Filtreler
        if (!empty($filters['area_id'])) {
            $sql .= " AND a.area_id = :area_id";
            $params['area_id'] = $filters['area_id'];
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND a.activity_date >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND a.activity_date <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        $sql .= "
            GROUP BY a.id
            ORDER BY a.activity_date ASC, MIN(ts.sort_order) ASC
            LIMIT :limit OFFSET :offset
        ";
        
        $params['limit'] = $limit;
        $params['offset'] = $offset;
        
        return $this->query($sql, $params);
    }
    
    /**
     * Toplam etkinlik sayısını getir (filtrelere göre)
     */
    public function getTotalCount($filters = [])
    {
        $sql = "SELECT COUNT(DISTINCT a.id) as total FROM vp_activities a WHERE 1=1";
        $params = [];
        
        if (!empty($filters['area_id'])) {
            $sql .= " AND a.area_id = :area_id";
            $params['area_id'] = $filters['area_id'];
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND a.activity_date >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND a.activity_date <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        $result = $this->query($sql, $params);
        return $result[0]['total'] ?? 0;
    }
}


