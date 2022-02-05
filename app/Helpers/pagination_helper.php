<?php
function getPagination($query,$debug=false){
    $db = db_connect();
    $result=setPaginationData($_GET);
    $total=$db->query('select count(*) as count '.$query['query'],$query['queryParams'])->getRowArray();
    $response['recordsTotal']=(isset($total['count']))?(int)$total['count']:0;
    if($result['orderByField']!='' && $result['orderBy']!=''){
        $query['query'].=' order by '.$db->escapeString($result['orderByField']).' '.$db->escapeString($result['orderBy']);
    }
    $query['query'].=' limit ? offset ?';
    $query['queryParams'][]=(int)$result['limit'];
    $query['queryParams'][]=(int)$result['offset'];
    $response['data']=$db->query('select '.$query['select'].' '.$query['query'],$query['queryParams'])->getResultArray();
    $response['recordsFiltered']=$response['recordsTotal'];//count($response['data']);
    $response['draw']= $result['draw'];
    if($debug){p([$db->getLastQuery(),$result,$response]);}
    return $response;
}
function setPaginationData($data)
{
    $array['draw'] = isset($data['draw']) ? $data['draw'] : 0;
    $array['limit'] =isset($data['length']) ? $data['length'] : 10;
    $array['offset']=isset($data['start']) ? $data['start']: 0;
    $array['orderByField']='';
    $array['orderBy']='';
    if(isset($data['order'])){
        for ($i = 0, $ien = count($data['order']); $i < $ien; $i++) {
            // Convert the column index into the column data property
            $columnIdx = intval($data['order'][$i]['column']);
            $requestColumn = $data['columns'][$columnIdx];
            $array['orderByField']= isset($requestColumn['data']) ? $requestColumn['data'] : '';
            $array['orderBy']= '';
            if ($requestColumn['orderable'] == 'true') {$array['orderBy'] = $data['order'][$i]['dir'] === 'asc' ?'ASC' :'DESC';}
        }
    }
    return $array;
}

function setPaginationDataApi($data,$sortBy,$sortOrder)
{
    $array['sort_by'] = (isset($data['sort_by']) && $data['sort_by']) ? $data['sort_by'] : $sortBy;
    $array['sort_order'] = (isset($data['sort_order']) && $data['sort_order']) ? $data['sort_order'] : $sortOrder;
    $array['limit'] = (isset($data['limit']) && $data['limit']) ? $data['limit'] : 20;
    $array['offset']= (isset($data['offset']) && $data['offset']) ? $data['offset']: 0;
    return $array;
}
function getPaginationApi($query,$db,$debug=false){
    $result=setPaginationDataApi($_REQUEST,$sortBy=null,'DESC');
    $total=$db->query('select '.$query['select'].' , count(*) as count '.$query['query'],$query['queryParams'])->row();
    $response['total']=(isset($total->count))?(int)$total->count:0;
    if($result['sort_by']!='' && $result['sort_order']!=''){
        $query['query'].=' order by '.$db->escape_str($result['sort_by']).' '.$db->escape_str($result['sort_order']);
    }
    $query['query'].=' limit ? offset ?';
    $query['queryParams'][]=$response['limit']=(int)$result['limit'];
    $query['queryParams'][]=$response['offset']=(int)$result['offset'];

    $response['offset']=$response['offset']+$response['limit'];

    $response['data']=$db->query('select '.$query['select'].' '.$query['query'],$query['queryParams'])->result();
    $response['count']=count($response['data']);
    if($debug){p([$db->last_query(),$result,$response]);}
    return $response;
}

?>