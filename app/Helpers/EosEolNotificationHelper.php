<?php

namespace App\Helpers;

use App\Models\Alat;
use Carbon\Carbon;

class EosEolNotificationHelper
{
    /**
     * Get all tools that are approaching EOS (within specified days)
     */
    public static function getEosWarningAlats($days = 30)
    {
        return Alat::eosExpiring($days)->get();
    }
    
    /**
     * Get all tools that are approaching EOL (within specified days)
     */
    public static function getEolWarningAlats($days = 90)
    {
        return Alat::eolExpiring($days)->get();
    }
    
    /**
     * Get all tools that have expired EOS
     */
    public static function getEosExpiredAlats()
    {
        return Alat::eosExpired()->get();
    }
    
    /**
     * Get all tools that have expired EOL
     */
    public static function getEolExpiredAlats()
    {
        return Alat::eolExpired()->get();
    }
    
    /**
     * Get notification messages for EOS/EOL warnings
     */
    public static function getNotifications()
    {
        $notifications = [];
        
        // EOS Expired
        $eosExpired = self::getEosExpiredAlats();
        if ($eosExpired->count() > 0) {
            $notifications[] = [
                'type' => 'danger',
                'icon' => 'fas fa-times-circle',
                'title' => 'EOS Berakhir',
                'message' => $eosExpired->count() . ' alat sudah melewati tanggal End of Service',
                'alats' => $eosExpired,
                'priority' => 'high'
            ];
        }
        
        // EOL Expired
        $eolExpired = self::getEolExpiredAlats();
        if ($eolExpired->count() > 0) {
            $notifications[] = [
                'type' => 'dark',
                'icon' => 'fas fa-skull-crossbones',
                'title' => 'EOL Berakhir',
                'message' => $eolExpired->count() . ' alat sudah melewati tanggal End of Life',
                'alats' => $eolExpired,
                'priority' => 'critical'
            ];
        }
        
        // EOS Warning (30 days)
        $eosWarning = self::getEosWarningAlats(30);
        if ($eosWarning->count() > 0) {
            $notifications[] = [
                'type' => 'warning',
                'icon' => 'fas fa-exclamation-triangle',
                'title' => 'Peringatan EOS',
                'message' => $eosWarning->count() . ' alat akan mencapai End of Service dalam 30 hari',
                'alats' => $eosWarning,
                'priority' => 'medium'
            ];
        }
        
        // EOL Warning (90 days)
        $eolWarning = self::getEolWarningAlats(90);
        if ($eolWarning->count() > 0) {
            $notifications[] = [
                'type' => 'info',
                'icon' => 'fas fa-info-circle',
                'title' => 'Peringatan EOL',
                'message' => $eolWarning->count() . ' alat akan mencapai End of Life dalam 90 hari',
                'alats' => $eolWarning,
                'priority' => 'low'
            ];
        }
        
        // Sort by priority
        usort($notifications, function($a, $b) {
            $priorities = ['critical' => 4, 'high' => 3, 'medium' => 2, 'low' => 1];
            return $priorities[$b['priority']] - $priorities[$a['priority']];
        });
        
        return $notifications;
    }
    
    /**
     * Get EOS/EOL statistics for dashboard
     */
    public static function getStatistics()
    {
        return [
            'eos_warning_30' => Alat::eosExpiring(30)->count(),
            'eos_warning_90' => Alat::eosExpiring(90)->count(),
            'eol_warning_90' => Alat::eolExpiring(90)->count(),
            'eol_warning_180' => Alat::eolExpiring(180)->count(),
            'eos_expired' => Alat::eosExpired()->count(),
            'eol_expired' => Alat::eolExpired()->count(),
            'total_with_eos' => Alat::whereNotNull('tanggal_eos')->count(),
            'total_with_eol' => Alat::whereNotNull('tanggal_eol')->count(),
        ];
    }
    
    /**
     * Get tools that need attention (expired or expiring soon)
     */
    public static function getToolsNeedingAttention()
    {
        $critical = collect();
        
        // Add EOS expired tools
        $critical = $critical->merge(
            Alat::eosExpired()->get()->map(function ($alat) {
                $alat->urgency = 'eos_expired';
                $alat->urgency_text = 'EOS Berakhir';
                $alat->urgency_class = 'danger';
                return $alat;
            })
        );
        
        // Add EOL expired tools
        $critical = $critical->merge(
            Alat::eolExpired()->get()->map(function ($alat) {
                $alat->urgency = 'eol_expired';
                $alat->urgency_text = 'EOL Berakhir';
                $alat->urgency_class = 'dark';
                return $alat;
            })
        );
        
        // Add EOS warning tools (30 days)
        $critical = $critical->merge(
            Alat::eosExpiring(30)->get()->map(function ($alat) {
                $alat->urgency = 'eos_warning';
                $alat->urgency_text = 'EOS dalam 30 hari';
                $alat->urgency_class = 'warning';
                return $alat;
            })
        );
        
        // Add EOL critical tools (30 days)
        $critical = $critical->merge(
            Alat::eolExpiring(30)->get()->map(function ($alat) {
                $alat->urgency = 'eol_critical';
                $alat->urgency_text = 'EOL dalam 30 hari';
                $alat->urgency_class = 'danger';
                return $alat;
            })
        );
        
        // Remove duplicates and sort by urgency
        $critical = $critical->unique('id');
        
        return $critical->sortBy(function ($alat) {
            $priorities = [
                'eol_expired' => 4,
                'eos_expired' => 3,
                'eol_critical' => 2,
                'eos_warning' => 1
            ];
            return -($priorities[$alat->urgency] ?? 0);
        });
    }
    
    /**
     * Format notification for display
     */
    public static function formatNotificationMessage($alat)
    {
        $messages = [];
        
        if ($alat->is_eos_expired) {
            $messages[] = "EOS berakhir {$alat->days_to_eos} hari yang lalu";
        } elseif ($alat->is_eos_warning) {
            $messages[] = "EOS dalam {$alat->days_to_eos} hari";
        }
        
        if ($alat->is_eol_expired) {
            $messages[] = "EOL berakhir " . abs($alat->days_to_eol) . " hari yang lalu";
        } elseif ($alat->is_eol_warning) {
            $messages[] = "EOL dalam {$alat->days_to_eol} hari";
        }
        
        return implode(' | ', $messages);
    }
    
    /**
     * Get recommended actions for a tool based on EOS/EOL status
     */
    public static function getRecommendedActions($alat)
    {
        $actions = [];
        
        if ($alat->is_eol_expired) {
            $actions[] = [
                'type' => 'danger',
                'text' => 'Segera ganti alat - sudah tidak didukung lagi',
                'icon' => 'fas fa-exclamation-circle'
            ];
        } elseif ($alat->is_eos_expired) {
            $actions[] = [
                'type' => 'warning',
                'text' => 'Pertimbangkan penggantian - support sudah berakhir',
                'icon' => 'fas fa-clock'
            ];
        } elseif ($alat->status_eol === 'eol_critical') {
            $actions[] = [
                'type' => 'warning',
                'text' => 'Persiapkan rencana penggantian',
                'icon' => 'fas fa-calendar-check'
            ];
        } elseif ($alat->status_eos === 'eos_warning') {
            $actions[] = [
                'type' => 'info',
                'text' => 'Evaluasi kebutuhan support',
                'icon' => 'fas fa-search'
            ];
        }
        
        return $actions;
    }
}
