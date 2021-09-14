<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.
/**
 * block_uploadvimeo view page
 *
 * @package block_suporte
 * @copyright 2021 CCEAD PUC-Rio
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require ('../../config.php');

define('TICKET_LIDO', '1');
define('TICKET_NAO_LIDO', '0');
define('TICKETS_BY_PAGE', 10);

global $DB, $USER, $COURSE, $OUTPUT, $PAGE, $ATENDIMENTODB;

$email = $USER->email;
$page = optional_param('page', 0, PARAM_INT);

$config = get_config('block_suporte');

$prefix = 'hesk_';

$dbclass = get_class($DB);
$ATENDIMENTODB = new $dbclass();
$ATENDIMENTODB->connect($config->dbhost, $config->dbuser, $config->dbpass, $config->dbname, $prefix);

$coursecontext = context_course::instance($COURSE->id);
$PAGE->set_url('/blocks/suporte/mytickets.php');
$PAGE->set_context($coursecontext);
$PAGE->set_heading('Canal de Atendimento');
$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('pluginname', 'block_suporte'));

require_login();

echo $OUTPUT->header();

echo "<h1>Histórico de atendimento</h1><br>";

$sql = "
        SELECT t.id, 
               t.trackid,
               t.status,
               CASE WHEN t.status = '0' THEN 'Novo' 
                    WHEN t.status = '1' THEN 'Aguardando resposta da equipe'
                    WHEN t.status = '2' THEN 'Aguardando resposta do usuário'
                    WHEN t.status = '3' THEN 'Resolvido'
                    WHEN t.status = '4' THEN 'Em progresso'
                    WHEN t.status = '5' THEN 'Em espera'   
               END AS statusdesc,
               t.subject,
               t.dt data_abertura,
               MAX(r.id) reply_id,
               MAX(r.dt) ultima_resposta,
               COUNT(r.replyto) total_resposta
          FROM {tickets} t
     LEFT JOIN {replies} r ON r.replyto = t.id
         WHERE t.email = :email
      GROUP BY 1,2,3,4,5,6
      ORDER BY t.id DESC ";

$tickets = $ATENDIMENTODB->get_records_sql($sql, array('email' => $email));

$total_tickets = count($tickets);

$tickets = $ATENDIMENTODB->get_records_sql($sql, array('email' => $email), $page*TICKETS_BY_PAGE, TICKETS_BY_PAGE);

if ($tickets) {
    
    $pagingbar = new paging_bar(
        $total_tickets,
        $page,
        TICKETS_BY_PAGE,
        new moodle_url('/blocks/suporte/mytickets.php', ['page' => $page]));
    
    echo $OUTPUT->render($pagingbar);
    
    if (property_exists($USER, 'realuser')) {
        $msg_alerta_admin = get_string('alerta_usuario_admin', 'block_suporte');
          
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">'. $msg_alerta_admin .          
          '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
    }
    
    echo '<p>'. $total_tickets . ' atendimentos</p>';

    echo '<div class="table-responsive">
    <table class="table table-striped">
           <tr>
              <th>ID rastreamento</th>
              <th>Assunto</th>
              <th>Criado em</th>
              <th>Total de respostas</th>
              <th>Última resposta</th>
              <th>Último remetente</th>
              <th>Status do ticket</th>
           </tr>';
    
    
    foreach ($tickets as $row) {
        
        $sql = "SELECT r.id, r.name, r.dt data_resposta, r.read, r.staffid
                  FROM hesk_replies r
                 WHERE r.id = :reply_id ";
        $reply = $ATENDIMENTODB->get_record_sql($sql, array('reply_id' => $row->reply_id));
        $dt_ultima_resposta = '';
        $ultimo_remetente = '';
        $url_ticket = $config->atendimentourl . '/ticket.php?track=' . $row->trackid;
        $css_link_admin = '';
        
        if ($reply) {
            $dt_ultima_resposta = $reply->data_resposta;
            $ultimo_remetente = $reply->name;
            
            if ($reply->read == TICKET_NAO_LIDO and $reply->staffid != 0) {
                $css_link_admin = 'class="font-weight-bold"';
            }
            
            // Se o ticket estiver nao lido e o usuario estiver acessando como.
            if ($reply->read == TICKET_NAO_LIDO and $reply->staffid != 0 and property_exists($USER, 'realuser')) {
                
                $url_ticket = $config->atendimentourl . '/admin/admin_ticket.php?track=' . $row->trackid;
                //$css_link_admin = 'class="font-italic font-weight-bold"';
            }
        }
        
        switch ($row->status) {
            case '0':
                $css = 'class="font-weight-bold text-danger"';
                break;
            case '1':
                $css = 'class="font-weight-bold text-primary"';
                break;
            case '2':
                $css = 'class="font-weight-bold text-warning"';
                break;
            case '3':
                $css = 'class="font-weight-bold text-success"';
                break;
            default:
                $css = '';
        }
        
        $data_abertura = new DateTime($row->data_abertura);
        $data_resposta = new DateTime($dt_ultima_resposta);
        
        echo "<tr $css_link_admin>
                  <td>{$row->trackid} (Número do ticket: {$row->id})</td>
                  <td><a href='{$url_ticket}' target='_blank'>{$row->subject}</a></td>
                  <td>{$data_abertura->format('d-m-Y H:i:s')}</td>
                  <td>{$row->total_resposta}</td>
                  <td>{$data_resposta->format('d-m-Y H:i:s')}</td>
                  <td>{$ultimo_remetente}</td>
                  <td {$css}>{$row->statusdesc}</td>
        </tr>";
    }
    
    echo "</table></div>";
} else {
    echo '<p class="">'. get_string('text_no_tickets', 'block_suporte', $email).'</p>';
}

echo $OUTPUT->footer();
    
