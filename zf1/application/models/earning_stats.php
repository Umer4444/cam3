<?

class Earning_stats extends App_Model
{

    protected $_name = "earning_stats";

    protected $_primary = "id";

    public function getParity()
    {

        // return array("parity" => $this->config('chips_parity'), "currency" => "USD");
        return array("parity" => config()->chips_parity, "currency" => "USD");
    }

    /**
     * Model stats din manage chips
     *
     * @param mixed $id
     * @param mixed $last_days
     * @return mixed
     */
    public function fetchModelStats($id = null, $last_days = 7)
    {

        if (!$id) return false;
        $select = $this->select()
            ->from($this->_name, array('total_amount' => 'sum(amount)', "type" => "type", "days" => "FLOOR(time/100)"))
            ->where("id_model=?", $id)
            ->where("time >=?", date("Ymd00", strtotime("-{$last_days} days")))
            ->group(array("type", "FLOOR(time/100)"));

        $results = $this->fetchAll($select);

        $data = array();

        for ($i = 0; $i <= $last_days; $i++) {
            $data[date("Ymd", strtotime("-{$i} days"))] = array('private' => 0, 'spy' => 0, 'tip' => 0, 'private_message' => 0, 'special_request' => 0);
        }

        $total_earnings['total'] = 0;
        foreach ($results as $result) {
            $data[$result->days][$result->type] = (int)ceil($result->total_amount);
            $total_earnings['total'] += (int)ceil($result->total_amount);
        }

        ksort($data);

        $ordered = array();
        $ordered[] = array("Day/Month", "Private Revenue", "Spy Revenue", "Tip Revenue", "Private message Revenue", "Special request Revenue");
        foreach ($data as $k => $v) {
            $ordered[] = array(substr($k, 6, 2) . '/' . substr($k, 4, 2), $v['private'], $v["spy"], $v["tip"], $v["private_message"], $v["special_request"],);
        }

        $dates = array(
            'chartdata' => ($ordered),
            'total' => $total_earnings['total'],
            'message' => "Total earnings last " . $last_days . " days"
        );
        return json_encode($dates);
    }

    /**
     * revenue stats din admin/Statistics
     *
     * @param mixed $fromDate
     * @param mixed $toDate
     * @param mixed $showBy
     * @return mixed
     */
    public function fetchRevenueStats($fromDate = null, $toDate = null, $showBy = "day", $model = null)
    {

        if (!$fromDate || !$toDate || $fromDate > $toDate) return false;

        $parity = $this->getParity();


        $diff = floor(($toDate - $fromDate) / 86400);

        if ($diff < 0) return false;

        $group = "FLOOR(time/100)";

        if ($showBy == "month") {
            $group = "FLOOR(time/10000)";
        }

        $select = $this->select()
            ->from($this->_name, array('total_amount' => 'sum(amount)', "type" => "type", "days" => $group))
            ->where("time >=?", date("Ymd00", $fromDate))
            ->where("time <=?", date("Ymd00", $toDate));
        if ($model)
            $select->where("id_model=?", (int)$model);

        $select->group(array("type", $group));

        $results = $this->fetchAll($select);

        $data = array();

        //       for($i=0; $i<=$diff; $i++){
//            $data[date("Ymd", $toDate-($i*86400))]  = array('private' => 0, 'spy' => 0, 'tip' => 0);
//        }
        $date_tmp = $fromDate;
        while ($date_tmp <= $toDate) {
            //if($sortBy == 'month') $date += 86400*30;

            if ($showBy == 'day') {
                $key_date = date("Ymd", $date_tmp);
                $date_tmp = $date_tmp + 86400;
            }
            if ($showBy == 'month') {
                $key_date = date("Ym", $date_tmp);
                $date_tmp = $date_tmp + 2628000;
            }

            $data[$key_date] = array('private' => 0, 'spy' => 0, 'tip' => 0, 'private_message' => 0, 'special_request' => 0,);
        }

        $total_earnings = array(
            'total' => 0,
            'private' => 0,
            'spy' => 0,
            'tip' => 0,
            'private_message' => 0,
            'special_request' => 0
        );
        foreach ($results as $result) {
            $data[$result->days][$result->type] = (int)ceil($result->total_amount);
            $total_earnings['total'] += (int)ceil($result->total_amount);
            $total_earnings[$result->type] += (int)ceil($result->total_amount);

        }

        ksort($data);

        $ordered = array();
        $ordered[] = array("Day/Month", "Private Revenue", "Spy Revenue", "Tip Revenue", "Special request Revenue", "Private message Revenue");
        foreach ($data as $k => $v) {
            if ($showBy == "day")
                $ordered[] = array(substr($k, 6, 2) . '/' . substr($k, 4, 2), $v['private'], $v["spy"], $v["tip"], $v["special_request"], $v["private_message"]);
            if ($showBy == "month")
                $ordered[] = array(substr($k, 4, 2) . '/' . substr($k, 0, 4), $v['private'], $v["spy"], $v["tip"], $v["special_request"], $v["private_message"]);
        }


        $dates = array(
            'chartdata' => ($ordered),
            'total' => $total_earnings['total'],
            'message' => "Total earnings between " . gmdate("d/m/Y", $fromDate) . " - " . gmdate("d/m/Y", $toDate),
            'earningtype' => array(
                array("Type", "Chips", "Revenue"),
                array("Private", floor($total_earnings['private'] * ($parity['parity'])), "$" . $total_earnings['private']),
                array("Spy", floor($total_earnings['spy'] * ($parity['parity'])), "$" . $total_earnings['spy']),
                array("Tip", floor($total_earnings['tip'] * ($parity['parity'])), "$" . $total_earnings['tip']),
                array("Private message", floor($total_earnings['private_message'] * ($parity['parity'])), "$" . $total_earnings['private_message']),
                array("Special request", floor($total_earnings['special_request'] * ($parity['parity'])), "$" . $total_earnings['special_request']),
                // array( "Total", $total_earnings['total']),
            )
        );
        return json_encode($dates);
    }

    public function convertChipsToMoney($amount)
    {
        $parity = $this->getParity();
        return floor($amount / $parity['parity']);
    }

    public function convertMoneyToChips($amount)
    {
        $parity = $this->getParity();
        return floor($amount * ($parity['parity']));
    }

}    