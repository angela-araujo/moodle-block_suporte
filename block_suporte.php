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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Suport Block
 * A block for suport
 * 
 * @package blocks
 * @author: CCEAD PUC-Rio (Ronnald Machado)
 * 
 */

class block_suporte extends block_base {
    
    public function init() {
        $this->title = get_string('suporte', 'block_suporte');
    }
    
	public function get_content() {
	    
    	global $USER, $COURSE, $DB;
    	
        if ($this->content !== null) {
          return $this->content;
        }
        
    	$nome = $USER->firstname;
    	$sobrenome = $USER->lastname;
    	$email = $USER->email;
    	$matricula = $USER->username;
        $curso = ($COURSE->id > 1) ? $COURSE->fullname : 'Página Inicial';
        $origem = '';
        
        if ($COURSE->category <> 0) {
            $reg_categoria = $DB->get_record('course_categories', array('id' => $COURSE->category));        
            $origem = $reg_categoria->name;
        }       
    	
    	$siteatendimento = 'https://atendimento.ccead.puc-rio.br/index.php?a=add';
    	
    	$CCE = (substr($matricula, 0, 2) == 'e0')? true: false;  	    
    	
        $this->content = new stdClass;

        if ($CCE) {
            
            $siteatendimento = 'https://cce.puc-rio.br/sitecce/website/website.dll/fale_conosco?nInst=cce';
            
            $content = '
            <div class="text-center" >
                <p style="font-size:0.9em">Para atendimento aos cursos da CCE, clique abaixo.</p>            
                <a href="'.$siteatendimento.'" data-href="'.$siteatendimento.'" class="btn btn-primary btn-xs" target="_blank">Atendimento</a>                      
            </div>';
            
            $this->content->text = $content;
            $this->content->footer = '';
            
        } else {
            
            $urlMyTickets = new moodle_url('/blocks/suporte/mytickets.php');
            $textNewTicket = get_string('text_newticket', 'block_suporte');
            $textHesk = get_string('text_mytickets', 'block_suporte');
            
            $this->content->text   = '
            <div class="" >
                <form id="formsuporte" action="' . $siteatendimento . '" method="POST" target="_blank">
                    <input type="hidden" id="moodle_fullname" name="moodle_fullname" value="' . $nome . " " . $sobrenome .'"/>
                    <input type="hidden" id="moodle_email" name="moodle_email" value="'. $email .'"/>
                    <input type="hidden" id="moodle_username" name="moodle_username" value="'. $matricula .'"/>
                    <input type="hidden" id="moodle_curso" name="moodle_curso" value="'. $curso .'"/>
                    <input type="hidden" id="moodle_origem" name="moodle_origem" value="'. $origem .'"/>
                    <input class="btn btn-primary btn-xs" type="submit" value="'.$textNewTicket.'"/>
                </form>
                <br>
                <div class="" >
                <a href="'.$urlMyTickets.'" class="" target="_blank">'.$textHesk.'</a>                      
            </div>
            </div>';
            $this->content->footer = '';
        }
        
        return $this->content;
        
      }
      
      function has_config(){
          return true;
      }
} 
