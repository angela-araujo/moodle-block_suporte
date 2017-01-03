<?php
class block_suporte extends block_base {
    public function init() {
        $this->title = get_string('suporte', 'block_suporte');
    }
	public function get_content() {
	global $USER;
    if ($this->content !== null) {
      return $this->content;
    }
	$nome = $USER->firstname;
	$sobrenome = $USER->lastname;
	$email= $USER->email;
	$matricula=$USER->username;
    $this->content         =  new stdClass;
    $this->content->text   = '<div class="text-center" ><p style="font-size:0.9em">Para suporte técnico no uso do ambiente, clique abaixo.</p><form action="http://atendimento.ccead.puc-rio.br/index.php?a=add" method="POST" target="_blank">
		<input type="hidden" id="moodle_fullname" name="moodle_fullname" value="' . $nome . " " . $sobrenome .'"/>
		<input type="hidden" id="moodle_email" name="moodle_email" value="'. $email .'"/>
		<input type="hidden" id="moodle_username" name="moodle_username" value="'. $matricula .'"/>
		<input class="btn btn-defaut btn-xs" type="submit" value="Atendimento"/></form></div>';;
    $this->content->footer = '';
 
    return $this->content;
  }
} 
