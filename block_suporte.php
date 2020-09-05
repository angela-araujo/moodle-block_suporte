<?php
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
	$email= $USER->email;
	$matricula=$USER->username;
    $curso=($COURSE->id > 1) ? $COURSE->fullname : 'Página Inicial';
    $reg_categoria = $DB->get_record('course_categories',array('id'=>$COURSE->category));
	$origem = $reg_categoria->name;
	
    $this->content         =  new stdClass;

    // ocultar categoria CCE
//    if($COURSE->category==56){
//        $this->content->text='';
//    }else{
        $this->content->text   = '<div class="text-center" ><p style="font-size:0.9em">Para suporte técnico no uso do ambiente, clique abaixo.</p><form action="https://atendimento.ccead.puc-rio.br/index.php?a=add" method="POST" target="_blank">
        <input type="hidden" id="moodle_fullname" name="moodle_fullname" value="' . $nome . " " . $sobrenome .'"/>
        <input type="hidden" id="moodle_email" name="moodle_email" value="'. $email .'"/>
        <input type="hidden" id="moodle_username" name="moodle_username" value="'. $matricula .'"/>
        <input type="hidden" id="moodle_curso" name="moodle_curso" value="'. $curso .'"/>
        <input type="hidden" id="moodle_origem" name="moodle_origem" value="'. $origem .'"/>
        <input class="btn btn-primary btn-xs" type="submit" value="Atendimento"/></form></div>';
//    }
    
    $this->content->footer = '';
 
    return $this->content;
  }
} 
