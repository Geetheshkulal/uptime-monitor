<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServerHealthController extends Controller
{
    public function check()
    {
        // Determine the operating system
        $os = PHP_OS_FAMILY;

        // Get CPU load
        $cpuLoad = $this->getCpuLoad($os);

        // Get memory usage
        $memoryUsage = memory_get_usage(true);

        // Get disk space
        $diskFreeSpace = disk_free_space('/');
        $diskTotalSpace = disk_total_space('/');

        // Calculate disk usage percentage
        $diskUsagePercentage = round((($diskTotalSpace - $diskFreeSpace) / $diskTotalSpace) * 100, 2);

        return response()->json([
            'os' => $os,
            'cpu_load' => $cpuLoad,
            'memory_usage' => $memoryUsage,
            'disk_free_space' => $diskFreeSpace,
            'disk_total_space' => $diskTotalSpace,
            'disk_usage_percentage' => $diskUsagePercentage,
        ]);
    }

    private function getCpuLoad($os)
    {
        if ($os === 'Windows') {
            return $this->getCpuLoadForWindows();
        } elseif ($os === 'Linux') {
            return $this->getCpuLoadForLinux();
        } elseif ($os === 'Darwin') { // macOS
            return $this->getCpuLoadForMac();
        } else {
            return 0; // Default to 0 if OS is unsupported
        }
    }

    private function getCpuLoadForWindows()
    {
        $load = 0;

        if (function_exists('exec')) {
            $output = [];
            exec('wmic cpu get loadpercentage', $output);

            foreach ($output as $line) {
                if (is_numeric(trim($line))) {
                    $load = (float) trim($line);
                    break;
                }
            }
        }

        return $load;
    }

    private function getCpuLoadForLinux()
    {
        $load = 0;

        if (file_exists('/proc/stat')) {
            $stat1 = file('/proc/stat');
            sleep(1); // Wait for 1 second
            $stat2 = file('/proc/stat');

            $info1 = explode(' ', preg_replace('!cpu +!', '', $stat1[0]));
            $info2 = explode(' ', preg_replace('!cpu +!', '', $stat2[0]));

            $dif = array_map(function ($v2, $v1) {
                return $v2 - $v1;
            }, $info2, $info1);

            $total = array_sum($dif);
            $load = ($dif[0] + $dif[1] + $dif[2]) / $total * 100;
        }

        return round($load, 2);
    }

    private function getCpuLoadForMac()
    {
        $load = 0;

        if (function_exists('exec')) {
            $output = [];
            exec('sysctl -n vm.loadavg', $output);

            if (!empty($output)) {
                $loadAvg = explode(' ', $output[0]);
                $load = isset($loadAvg[0]) ? (float) $loadAvg[0] * 100 : 0; // Convert to percentage
            }
        }

        return $load;
    }
}