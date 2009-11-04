<?php
require "Console/Getopt.php";
error_reporting(0);
$con = new Console_Getopt;
$args = $con->readPHPArgv();
array_shift($args);
$shortoptions = 'acg:hiIlmMrRsStTuUO:vzZ';
$retval = $con->getopt( $args, $shortoptions);
if(is_object($retval)) {
    usage();
}
$opt['O'] = 20;
$opt['l'] = '1';
//$opt['t'] = '';

foreach ($retval[0] as $kv_array) {
    $opt[$kv_array[0]] = $kv_array[1];
}
print_r($opt);
($dataFile = $retval[1][0]) || usage();
if(($DATA = fopen($dataFile, "r")) == FALSE) {
    print "Failed to open $dataFile for reading\n";
    exit(1);
}

$cfg = array();
parse_info('HEADER', $DATA, $cfg);

$callstack = array();
$calls = array();
$indent_cur = 0;
$file_hash = array();
$mem = array();
$t_rtime = 0;
$t_stime = 0;
$t_utime = 0;
$c_rtimes = array();
$c_stimes = array();
$c_utimes = array();
$rtimes = array();
$stimes = array();
$utimes = array();
$rtotal = 0;
$stotal = 0;
$utotal = 0;
$last_memory = 0;

$symbol_hash = array();
$symbol_type = array();

while($line = fgets($DATA)) {
    $line = rtrim($line);
    if(preg_match("/^END_TRACE/", $line)){
        break;
    }
    list($token, $data) = preg_split("/ /",$line, 2);
    if($token == '!') {
  list ($index, $file) = preg_split("/ /", $data, 2);
  $file_hash[$index] = $file;
  continue;
    }
    if( $token == '&') {
        list ($index, $name, $type) = preg_split("/ /", $data, 3);
        $symbol_hash[$index] = $name;
  $symbol_type[$index] = $type;
        continue;
    }
    if( $token == '+') {
        list($index, $file, $line) = preg_split("/ /",$data, 3);
        if(array_key_exists('i',$opt) && $symbol_type[$index] == 1) {
            continue;
        }
        $index_cur = $index;
        $calls[$index_cur]++;
        array_push($callstack, $index_cur);
        if(array_key_exists('T', $opt)) {
            if(array_key_exists('c', $opt)) {
                printf("%2.02f ", $rtotal/1000000);
            }
            print str_repeat("  ", $indent_cur).$symbol_hash[$index_cur]."\n";
      if(array_key_exists('m', $opt)) {
    print str_repeat("  ", $indent_cur)."C: $file_hash[$file]:$line M: $memory\n";
      }
  }
        elseif(array_key_exists('t', $opt)) {
            if ( $indent_last == $indent_cur && $index_last == $index_cur ) {
                $repcnt++;
            }
            else {
                if ( $repcnt ) {
                    $repstr = ' ('.++$repcnt.'x)';
                }
                if(array_key_exists('c', $opt)) {
                    printf("%2.02f ", $rtotal/1000000);
                }
                print str_repeat("  ", $indent_last).$symbol_hash[$index_last].$repstr."\n";
    if(array_key_exists('m', $opt)) {
       print str_repeat("  ", $indent_cur)."C: $file_hash[$file_last]:$line_last M: $memory\n";
    }
                $repstr = '';
                $repcnt = 0;
                $index_last = $index_cur;
                $indent_last = $indent_cur;
    $file_last = $file;
    $line_last = $line;
            }
        }
  $indent_cur++;
        continue;
    }
    if( $token == '@') {
        list($file_no, $line_no, $ut, $st, $rt) = preg_split("/ /", $data);
        $top = array_pop($callstack);
        $utimes[$top] += $ut;
        $utotal += $ut;
        $stimes[$top] += $st;
        $stotal += $st;
        $rtimes[$top] += $rt;
        $rtotal += $rt;
        array_push($callstack, $top);
  foreach ($callstack as $stack_element) {
            $c_utimes[$stack_element] += $ut;
            $c_stimes[$stack_element] += $st;
            $c_rtimes[$stack_element] += $rt;
        }
        continue;
    }
    if ($token == '-') {
        list  ($index, $memory) = preg_split("/ /", $data, 2);
        if(array_key_exists('i',$opt) && $symbol_type[$index] == 1)
        {
            continue;
        }
        $mem[$index] += ($memory - $last_memory);
        $last_memory = $memory;
        $indent_cur--;
        $tmp = array_pop($callstack);
        continue;
    }
}
parse_info('FOOTER', $DATA, $cfg);
$sort = 'by_time';
if(array_key_exists('l', $opt)) { $sort = 'by_calls'; }
if(array_key_exists('m', $opt)) { $sort = 'by_mem'; }
if(array_key_exists('a', $opt)) { $sort = 'by_name'; }
if(array_key_exists('v', $opt)) { $sort = 'by_avgcpu'; }
if(array_key_exists('r', $opt)) { $sort = 'by_rtime'; }
if(array_key_exists('R', $opt)) { $sort = 'by_c_rtime'; }
if(array_key_exists('s', $opt)) { $sort = 'by_stime'; }
if(array_key_exists('S', $opt)) { $sort = 'by_c_stime'; }
if(array_key_exists('u', $opt)) { $sort = 'by_utime'; }
if(array_key_exists('U', $opt)) { $sort = 'by_c_utime'; }
if(array_key_exists('Z', $opt)) { $sort = 'by_c_time'; }
if( !count($symbol_hash)) {
    continue;
}

printf("
Trace for %s
Total Elapsed Time = %4.2f
Total System Time  = %4.2f
Total User Time    = %4.2f
", $cfg['caller'], $rtotal/1000000, $stotal/1000000, $utotal/1000000);
print "\n
         Real         User        System             secs/    cumm
%Time (excl/cumm)  (excl/cumm)  (excl/cumm) Calls    call    s/call  Memory Usage Name
--------------------------------------------------------------------------------------\n";
$l = 0;
$itotal = 0;
$percall = 0;
$cpercall = 0;

uksort($symbol_hash, $sort);
foreach (array_keys($symbol_hash) as $j) {
    if(array_key_exists('i', $opt) && $symbol_type[$j] == 1) {
        continue;
    }
    if ($l++ <  $opt['O']) {
        $pcnt = 100*($stimes[$j] + $utimes[$j])/($utotal + $stotal + $itotal);
        $c_pcnt = 100* ($c_stimes[$j] + $c_utimes[$j])/($utotal + $stotal + $itotal);
        $rsecs = $rtimes[$j]/1000000;
        $ssecs = $stimes[$j]/1000000;
        $usecs = $utimes[$j]/1000000;
        $c_rsecs = $c_rtimes[$j]/1000000;
        $c_ssecs = $c_stimes[$j]/1000000;
        $c_usecs = $c_utimes[$j]/1000000;
        $ncalls = $calls[$j];
  if(array_key_exists('z', $opt)) {
          $percall = ($usecs + $ssecs)/$ncalls;
          $cpercall = ($c_usecs + $c_ssecs)/$ncalls;
                if($utotal + $stotal) {
        $pcnt = 100*($stimes[$j] + $utimes[$j])/($utotal + $stotal);
                }
                else {
                    $pcnt = 100;
                }
  }
  if(array_key_exists('Z', $opt)) {
          $percall = ($usecs + $ssecs)/$ncalls;
          $cpercall = ($c_usecs + $c_ssecs)/$ncalls;
                if($utotal + $stotal) {
        $pcnt = 100*($c_stimes[$j] + $c_utimes[$j])/($utotal + $stotal);
                }
                else {
                    $pcnt = 100;
                }
  }
  if(array_key_exists('r', $opt)) {
          $percall = ($rsecs)/$ncalls;
          $cpercall = ($c_rsecs)/$ncalls;
                if($rtotal) {
        $pcnt = 100*$rtimes[$j]/$rtotal;
                }
                else {
                    $pcnt = 100;
                }
  }
  if(array_key_exists('R', $opt)) {
          $percall = ($rsecs)/$ncalls;
          $cpercall = ($c_rsecs)/$ncalls;
                if($rtotal) {
        $pcnt = 100*$c_rtimes[$j]/$rtotal;
                }
                else {
                    $pcnt = 100;
                }
  }
  if(array_key_exists('u', $opt)) {
          $percall = ($usecs)/$ncalls;
          $cpercall = ($c_usecs)/$ncalls;
                if($utotal) {
        $pcnt = 100*$utimes[$j]/$utotal;
                }
                else {
                    $pcnt = 100;
                }
  }
  if(array_key_exists('U', $opt)) {
          $percall = ($usecs)/$ncalls;
          $cpercall = ($c_usecs)/$ncalls;
                if($utotal) {
        $pcnt = 100*$c_utimes[$j]/$utotal;
                }
                else {
                    $pcnt = 100;
                }
  }
  if(array_key_exists('s', $opt)) {
          $percall = ($ssecs)/$ncalls;
          $cpercall = ($c_ssecs)/$ncalls;
                if($stotal) {
        $pcnt = 100*$stimes[$j]/$stotal;
                }
                else {
                    $pcnt = 100;
                }
  }
  if(array_key_exists('S', $opt)) {
          $percall = ($ssecs)/$ncalls;
          $cpercall = ($c_ssecs)/$ncalls;
                if($stotal) {
        $pcnt = 100*$c_stimes[$j]/$stotal;
                }
                else {
                    $pcnt = 100;
                }
  }
//        $cpercall = ($c_usecs + $c_ssecs)/$ncalls;
        $mem_usage = $mem[$j];
        $name = $symbol_hash[$j];
        printf("%3.01f %2.02f %2.02f  %2.02f %2.02f  %2.02f %2.02f  %4d  %2.04f   %2.04f %12d %s\n", $pcnt, $rsecs, $c_rsecs, $usecs, $c_usecs, $ssecs, $c_ssecs, $ncalls, $percall, $cpercall, $mem_usage, $name);
    }
}


function usage() {
print <<<EOD
pprofp <flags> <trace file>
    Sort options
    -a          Sort by alphabetic names of subroutines.
    -l          Sort by number of calls to subroutines
    -m          Sort by memory used in a function call.
    -r          Sort by real time spent in subroutines.
    -R          Sort by real time spent in subroutines (inclusive of child calls).
    -s          Sort by system time spent in subroutines.
    -S          Sort by system time spent in subroutines (inclusive of child calls).
    -u          Sort by user time spent in subroutines.
    -U          Sort by user time spent in subroutines (inclusive of child calls).
    -v          Sort by average amount of time spent in subroutines.
    -z          Sort by user+system time spent in subroutines. (default)

    Display options
    -c          Display Real time elapsed alongside call tree.
    -i          Suppress reporting for php builtin functions
    -O <cnt>    Specifies maximum number of subroutines to display. (default 15)
    -t          Display compressed call tree.
    -T          Display uncompressed call tree.

EOD;
    exit(1);
}

function parse_info($tag, $datasource, &$cfg) {
    while($line = fgets($datasource)) {
        $line = rtrim($line);
        if(preg_match("/^END_$tag$/", $line)) {
            break;
        }
        if(preg_match("/(\w+)=(.*)/", $line, $matches)) {
            $cfg[$matches[1]] = $matches[2];
        }
    }
}

function num_cmp($a, $b) {
    if (intval($a) > intval($b)) { return 1;}
    elseif(intval($a) < intval($b)) { return -1;}
    else {return 0;}
}

function by_time($a,$b) {
    global $stimes;
    global $utimes;
    return num_cmp(($stimes[$b] + $utimes[$b]),($stimes[$a] + $utimes[$a]));
}

function by_c_time($a,$b) {
    global $c_stimes;
    global $c_utimes;
    return num_cmp(($c_stimes[$b] + $c_utimes[$b]),($c_stimes[$a] + $c_utimes[$a]));
}

function by_avgcpu($a,$b) {
    global $stimes;
    global $utimes;
    global $calls;
    return num_cmp(($stimes[$b] + $utimes[$b])/$calls[$b],($stimes[$a] + $utimes[$a])/$calls[$a]);
}

function by_calls($a, $b) {
    global $calls;
    return num_cmp($calls[$b], $calls[$a]);
}
function by_rtime($a,$b) { global $rtimes; return num_cmp($rtimes[$b], $rtimes[$a]);}
function by_c_rtime($a,$b) { global $c_rtimes; return num_cmp($c_rtimes[$b], $c_rtimes[$a]); }
function by_stime($a,$b) { global $stimes; return num_cmp($stimes[$b], $stimes[$a]); }
function by_c_stime($a,$b) { global $c_stimes; return num_cmp($c_stimes[$b], $c_stimes[$a]); }
function by_utime($a,$b) { global $utimes; return num_cmp($utimes[$b], $utimes[$a]); }
function by_c_utime($a,$b) { global $c_utimes; return num_cmp($c_utimes[$b], $c_utimes[$a]); }
function by_mem($a, $b) { global $mem; return num_cmp($mem[$b], $mem[$a]); }

?>