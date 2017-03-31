<?php
/**
 * @file
 * Contains \Drupal\sistema_custom\Form\ApplicationForm.
 */

namespace Drupal\sistema_custom\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Component\Utility\UrlHelper;
use \Drupal\node\Entity\Node;
use Drupal\field\FieldConfigInterface;
use Drupal\field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\WidgetPluginManager;
use Drupal\field\FieldableEntityInterface;
use \Drupal\user\Entity\User;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax;
use Drupal\Core\Url;


/**
 * Application form.
 */
class ApplicationForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'sistema_custom_application_form';
  }
  
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $arg = NULL) {
    $StudentNid = $arg;
    if (!empty($StudentNid) && $StudentNid !== 'new') {
      $Student = Node::load($StudentNid);
    }
    // print '<pre>';
    // print_r($form['medical']['medical_waiver']);
    // print '</pre>';
    $form['student_nid'] = array(
      '#type' => 'hidden',
      '#title' => t('Student NID - to be hidden'),
      '#default_value' => $Student->nid->value,
    );
    $form['first'] = array(
      '#type' => 'textfield',
      '#title' => t('Student first name'),
      '#required' => TRUE,
      '#default_value' => $Student->field_student_first->value,
    );
    $form['last'] = array(
      '#type' => 'textfield',
      '#title' => t('Student last name'),
      '#required' => TRUE,
      '#default_value' => $Student->field_student_last->value,
    );
    $form['relationship'] = array(
      '#type' => 'textfield',
      '#title' => t('Relationship to student'),
      '#required' => TRUE,
      '#suffix' => 'Mother/Father/Grandparent/etc',
      '#default_value' => $Student->field_student_rel_to_child->value,
    );
    $form['dob'] = array(
      '#type' => 'textfield',
      '#title' => t('Student date of birth'),
      '#required' => TRUE,
      '#suffix' => 'YYYY-MM-DD',
      '#default_value' => $Student->field_student_dob->value,
    );
    $form['school'] = array(
      '#type' => 'select',
      '#title' => t('School'),
      '#required' => TRUE,
      '#options' => array ('' => '- Select -',
        'Military Trail' => 'Military Trail',
        'Parkdale' => 'Parkdale',
        'St. Martin de Porres' => 'St. Martin de Porres',
        'Yorkwoods' => 'Yorkwoods',
      ),//TODO: GET OPTS FROM CT =======
      '#default_value' => $Student->field_student_school->value,
    );
    $form['grade'] = array(
      '#type' => 'number', // MAKE NUMBER FIELD ======
      '#title' => t('Current Grade'),
      '#required' => TRUE,
      '#default_value' => $Student->field_student_grade->value,
      '#suffix' => 'Enter 0 for SK',
    );
    $form['teacher_title'] = array(
      '#type' => 'select',
      '#title' => t('Teacher Title'),
      '#required' => TRUE,
      '#options' => array ('' => '- Select -',
        'Ms.' => 'Ms.',
        'Mrs.' => 'Mrs.',
        'Miss' => 'Miss',
        'Mr.' => 'Mr.',
        'Dr.' => 'Dr.',
      ),//TODO: GET OPTS FROM CT =======
      '#default_value' => $Student->field_student_teacher_title->value,
    );
    $form['teacher_last'] = array(
      '#type' => 'textfield',
      '#title' => t('Teacher last name'),
      '#required' => TRUE,
      '#default_value' => $Student->field_student_teacher_last->value,
    );
    $form['pref_string_inst'] = array(
      '#type' => 'select',
      '#title' => t('Student\'s preferred string instrument'),
      '#options' => array ('' => '- Select -',
        'Violin' => 'Violin',
        'Cello' => 'Cello',
      ),
      '#default_value' => $Student->field_student_pref_string_inst->value,
    );
    $form['second_parent'] = array (
      '#type' => 'fieldset',
      '#title' => 'Second parent info',
    );
    $form['second_parent']['second_parent_first'] = array(
      '#type' => 'textfield',
      '#title' => t('Second parent first name'),
      '#default_value' => $Student->field_student_sec_parent_first->value,
    );
    $form['second_parent']['second_parent_last'] = array(
      '#type' => 'textfield',
      '#title' => t('Second parent last name'),
      '#default_value' => $Student->field_student_sec_parent_last->value,
    );
    $form['second_parent']['second_parent_rel'] = array(
      '#type' => 'textfield',
      '#title' => t('Second parent relationship to student'),
      '#default_value' => $Student->field_student_sec_parent_rel->value,
    );
    $form['second_parent']['second_parent_email'] = array(
      '#type' => 'email',
      '#title' => t('Second parent email'),
      '#default_value' => $Student->field_student_sec_parent_email->value,
    );
    $form['second_parent']['second_parent_pp'] = array(
      '#type' => 'textfield',
      '#title' => t('Second parent primary phone'),
      '#default_value' => $Student->field_student_sec_parent_pp->value,
      '#suffix' => 'xxx-xxx-xxxx',
    );
    $form['second_parent']['second_parent_wp'] = array(
      '#type' => 'textfield',
      '#title' => t('Second parent work phone'),
      '#default_value' => $Student->field_student_sec_parent_wp->value,
      '#suffix' => 'xxx-xxx-xxxx',
    );
    $form['second_parent']['second_parent_cp'] = array(
      '#type' => 'textfield',
      '#title' => t('Second parent cell phone'),
      '#default_value' => $Student->field_student_sec_parent_cp->value,
      '#suffix' => 'xxx-xxx-xxxx',
    );
    $form['auth_pickup'] = array(
      '#type' => 'textarea',
      '#title' => t('People authorized to pick-up your child after program at 5:10pm or 5:45pm'),
      '#required' => TRUE,
      '#default_value' => $Student->field_student_auth_for_pickup->value,
    );
    $form['emerg_1'] = array (
      '#type' => 'fieldset',
      '#title' => 'Emergency Contact 1',
    );
    $form['emerg_1']['emerg_contact_1'] = array(
      '#type' => 'textfield',
      '#title' => t('Emergency contact 1 name'),
      '#required' => TRUE,
      '#default_value' => $Student->field_student_emerg_contact_1->value,
    );
    $form['emerg_1']['emerg_number_1'] = array(
      '#type' => 'textfield',
      '#title' => t('Emergency contact 1 number'),
      '#required' => TRUE,
      '#default_value' => $Student->field_student_emerg_number_1->value,
      '#suffix' => 'xxx-xxx-xxxx',
    );
    $form['emerg_1']['emerg_1_rel'] = array(
      '#type' => 'textfield',
      '#title' => t('Emergency contact 1 relationship to student'),
      '#required' => TRUE,
      '#suffix' => 'Mother/Father/Grandparent/etc',
      '#default_value' => $Student->field_student_emerg_1_rel->value,
    );
    $form['emerg_2'] = array (
      '#type' => 'fieldset',
      '#title' => 'Emergency Contact 2',
    );
    $form['emerg_2']['emerg_contact_2'] = array(
      '#type' => 'textfield',
      '#title' => t('Emergency contact 2 name'),
      '#default_value' => $Student->field_student_emerg_contact_2->value,
    );
    $form['emerg_2']['emerg_number_2'] = array(
      '#type' => 'textfield',
      '#title' => t('Emergency contact 2 number'),
      '#default_value' => $Student->field_student_emerg_number_2->value,
      '#suffix' => 'xxx-xxx-xxxx',
    );
    $form['emerg_2']['emerg_2_rel'] = array(
      '#type' => 'textfield',
      '#title' => t('Emergency contact 2 relationship to student'),
      '#suffix' => 'Mother/Father/Grandparent/etc',
      '#default_value' => $Student->field_student_emerg_2_rel->value,
    );
    $form['allergies'] = array(
      '#type' => 'textarea',
      '#title' => t('List Your Child’s Medical Conditions/Allergies'),
      '#required' => TRUE,
      '#default_value' => $Student->field_student_allergies->value,
    );
    $form['capable'] = array(
      '#type' => 'select',
      '#title' => t('Is Your Child Physically Capable of Singing and playing an instrument?'),
      '#required' => TRUE,
      '#options' => array ('' => '-- Please select --',
        'Yes' => 'Yes',
        'No' => 'No',
      ),
    );
    $form['epi_pen'] = array(
      '#type' => 'select',
      '#title' => t('Has Your Child Been Prescribed An Epi-Pen?'),
      '#required' => TRUE,
      '#options' => array ('' => '-- Please select --',
        'Yes' => 'Yes',
        'No' => 'No',
      ),
    );
    $form['inhaler'] = array(
      '#type' => 'select',
      '#title' => t('Has Your Child Been Prescribed An Asthma Inhaler?'),
      '#required' => TRUE,
      '#options' => array ('' => '-- Please select --',
        'Yes' => 'Yes',
        'No' => 'No',
      ),
    );
    $form['demographic'] = array (
      '#type' => 'fieldset',
      '#title' => 'Family Demographic Information (Optional)',
    );
    $form['demographic']['adults'] = array(
      '#type' => 'number',
      '#title' => t('Adults'),
      '#default_value' => $Student->field_student_dem_adults->value,
      '#prefix' => t('Although this information is optional, it will allow us to assess the financial need of the applicant. If you have any special circumstances you would like us to consider, please indicate below.'),
    );
    $form['demographic']['children'] = array(
      '#type' => 'number',
      '#title' => t('Children'),
      '#default_value' => $Student->field_student_dem_children->value,
    );
    $form['demographic']['income'] = array(
      '#type' => 'number',
      '#title' => t('Gross family income'),
      '#default_value' => $Student->field_student_dem_income->value,
    );
    $form['demographic']['languages'] = array(
      '#type' => 'textfield',
      '#title' => t('Languages spoken at home'),
      '#default_value' => $Student->field_student_dem_languages->value,
    );
    $form['demographic']['circumstances'] = array(
      '#type' => 'textarea',
      '#title' => t('Are there any special circumstances you would like us to consider?'),
      '#default_value' => $Student->field_student_dem_circ->value,
    );
    $form['medical'] = array (
      '#type' => 'fieldset',
      '#title' => 'Medical Waiver',
    );
    $form['medical']['link'] = array (
      '#type' => 'link',
      '#title' => 'View Medical Waiver',
      '#url' => Url::fromRoute('sistema_custom.page', array('nid' => 342)),
      '#attributes' => array (
        'class' => array('use-ajax'),
        'data-dialog-type' => 'modal',
        'data-dialog-options' => json_encode(array('height' => '400', 'width' => '700')),
      ),
    );
    $form['medical']['medical_waiver'] = array(
      '#type' => 'checkbox',
      '#title' => t('I agree to the terms of the Medical Waiver'),
      '#required' => TRUE,
      '#options' => array(1 => 'YES, I have authority to act fully on behalf of my child and agree to Sistema Toronto’s terms and conditions regarding medical care for my child.'),
    );
    $form['media'] = array (
      '#type' => 'fieldset',
      '#title' => 'Media Waiver',
    );
    $form['media']['link'] = array (
      '#type' => 'link',
      '#title' => 'View Media Waiver',
      '#url' => Url::fromRoute('sistema_custom.page', array('nid' => 344)),
      '#attributes' => array (
        'class' => array('use-ajax'),
        'data-dialog-type' => 'modal',
        'data-dialog-options' => json_encode(array('height' => '400', 'width' => '700')),
      ),
    );
    $form['media']['media_waiver'] = array(
      '#type' => 'radios',
      '#title' => t('I agree to the terms of the Media Release Waiver'),
      '#required' => TRUE,
      '#options' => array (
        'yes' => 'YES, I have read and agree to Sistema Toronto’s Media Release policy.',
        'no' => 'NO, I have read and do not agree to Sistema Toronto’s Media Release policy.',
      ),
    );
    $form['academic'] = array (
      '#type' => 'fieldset',
      '#title' => 'Academic Waiver',
    );
    $form['academic']['link'] = array (
      '#type' => 'link',
      '#title' => 'View Academic Waiver',
      '#url' => Url::fromRoute('sistema_custom.page', array('nid' => 345)),
      '#attributes' => array (
        'class' => array('use-ajax'),
        'data-dialog-type' => 'modal',
        'data-dialog-options' => json_encode(array('height' => '400', 'width' => '700')),
      ),
    );
    $form['academic']['academic_waiver'] = array(
      '#type' => 'radios',
      '#title' => t('I agree to the terms of the Academic Release Waiver'),
      '#required' => TRUE,
      '#options' => array (
        'yes' => 'YES, I authorize Sistema Toronto to request and access my child\'s TDSB/TCDSB report card records.',
        'no' => 'NO, I do not authorize Sistema Toronto to request and access my child\'s TDSB/TCDSB report card records.',
      ),
    );
    $form['conduct'] = array (
      '#type' => 'fieldset',
      '#title' => 'Code of Conduct Waiver',
    );
    $form['conduct']['link'] = array (
      '#type' => 'link',
      '#title' => 'View Code of Conduct Waiver',
      '#url' => Url::fromRoute('sistema_custom.page', array('nid' => 406)),
      '#attributes' => array (
        'class' => array('use-ajax'),
        'data-dialog-type' => 'modal',
        'data-dialog-options' => json_encode(array('height' => '400', 'width' => '700')),
      ),
    );
    $form['conduct']['conduct_waiver'] = array(
      '#type' => 'checkbox',
      '#title' => t('I agree to the terms of the Code of Conduct Waiver'),
      '#required' => TRUE,
      '#options' => array (
        1 => 'YES, I have read and agree to Sistema Toronto’s Program Attendance, Code of Student Conduct and Assessment Period Policy.',
      ),
    );
    $form['liability'] = array (
      '#type' => 'fieldset',
      '#title' => 'Liability Release',
    );
    $form['liability']['link'] = array (
      '#type' => 'link',
      '#title' => 'View Liability Release',
      '#url' => Url::fromRoute('sistema_custom.page', array('nid' => 407)),
      '#attributes' => array (
        'class' => array('use-ajax'),
        'data-dialog-type' => 'modal',
        'data-dialog-options' => json_encode(array('height' => '400', 'width' => '700')),
      ),
    );
    $form['liability']['liability_release'] = array(
      '#type' => 'checkbox',
      '#title' => t('I agree to the terms of the Liability Release'),
      '#required' => TRUE,
      '#options' => array (
        1 => 'YES, I confirm that I have read and understood the information contained in this Student Application in its entirety, and give consent for such information herein to be held by Sistema Toronto Academy.  Furthermore, I hereby commit to uphold my responsibilities and give consent for my child’s/ward’s full participation in the Sistema Toronto PLAYING TO POTENTIAL program.',
      ),
    );
    $form['lending'] = array (
      '#type' => 'fieldset',
      '#title' => 'Instrument Lending Policy',
    );
    $form['lending']['link'] = array (
      '#type' => 'link',
      '#title' => 'View Instrument Lending Policy',
      '#url' => Url::fromRoute('sistema_custom.page', array('nid' => 408)),
      '#attributes' => array (
        'class' => array('use-ajax'),
        'data-dialog-type' => 'modal',
        'data-dialog-options' => json_encode(array('height' => '400', 'width' => '700')),
      ),
    );
    $form['lending']['lending_policy'] = array(
      '#type' => 'checkbox',
      '#title' => t('I agree to the terms of the Instrument Lending Policy'),
      '#required' => TRUE,
      '#options' => array (
        1 => 'YES, I have read and agree to Sistema Toronto’s Instrument Lending Policy.',
      ),
    );
    $form['comments'] = array(
      '#type' => 'textarea',
      '#title' => t('Comments'),
    );
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );
    return $form;
  }
  
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    
    if (phoneHelper($form_state->getValue('emerg_number_1'))) {
      $form_state->setErrorByName('emerg_number_1', $this->t('The phone number must be in the format xxx-xxx-xxxx'));
    }
    if (phoneHelper($form_state->getValue('emerg_number_2'))) {
      $form_state->setErrorByName('emerg_number_2', $this->t('The phone number must be in the format xxx-xxx-xxxx'));
    }
    if (phoneHelper($form_state->getValue('second_parent_pp'))) {
      $form_state->setErrorByName('second_parent_pp', $this->t('The phone number must be in the format xxx-xxx-xxxx'));
    }
    if (phoneHelper($form_state->getValue('second_parent_wp'))) {
      $form_state->setErrorByName('second_parent_wp', $this->t('The phone number must be in the format xxx-xxx-xxxx'));
    }
    if (phoneHelper($form_state->getValue('second_parent_cp'))) {
      $form_state->setErrorByName('second_parent_cp', $this->t('The phone number must be in the format xxx-xxx-xxxx'));
    }
    if (dobHelper($form_state->getValue('dob'))) {
      $form_state->setErrorByName('dob', $this->t('Date of birth must be in the format YYYY-MM-DD'));
    }
    if (gradeHelper($form_state->getValue('grade')) && empty($form_state->getValue('student_nid'))) {
      $form_state->setErrorByName('grade', $this->t('Grade for new registrations must be 0 to 4.  Enter 0 for SK.'));
    }
  }
  /**
   * {@inheritdoc}
   */
  
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    
    $field = array();
    foreach ($form_state->getValues() as $key => $value) {
      $field[$key] = $value;
    }
    //If it is a new student, create a new node
    if (empty($field['student_nid'])) {
      $student = Node::create([
        'type'        	=> 'student',
        'title'       	=> $field['first'] . ' ' . $field['last'],
      ]);
      $student->field_student_year_joined_sist->value = date('Y');
    }
    else {
      //There is an existing student, update that node
      $student = Node::load($field['student_nid']);
    }
    $student->field_student_rel_to_child->value = $field['relationship'];
    $student->field_student_first->value = $field['first'];
    $student->field_student_last->value = $field['last'];
    $student->field_student_dob->value = $field['dob'];
    $student->field_student_school->value = $field['school'];
    $student->field_student_grade->value = $field['grade'];
    $student->field_student_teacher_title->value = $field['teacher_title'];
    $student->field_student_teacher_last->value = $field['teacher_last'];
    $student->field_student_pref_string_inst->value = $field['pref_string_inst'];
    $student->field_student_dem_adults->value = $field['adults'];
    $student->field_student_dem_children->value = $field['children'];
    $student->field_student_dem_income->value = $field['income'];
    $student->field_student_dem_languages->value = $field['languages'];
    $student->field_student_dem_circ->value = $field['circ'];
    $student->field_student_sec_parent_first->value = $field['second_parent_first'];
    $student->field_student_sec_parent_last->value = $field['second_parent_last'];
    $student->field_student_sec_parent_rel->value = $field['second_parent_rel'];
    $student->field_student_sec_parent_email->value = $field['second_parent_email'];
    $student->field_student_sec_parent_pp->value = $field['second_parent_pp'];
    $student->field_student_sec_parent_wp->value = $field['second_parent_wp'];
    $student->field_student_sec_parent_cp->value = $field['second_parent_cp'];
    $student->field_student_emerg_number_1->value = $field['emerg_number_1'];
    $student->field_student_emerg_contact_1->value = $field['emerg_contact_1'];
    $student->field_student_emerg_1_rel->value = $field['emerg_1_rel'];
    $student->field_student_emerg_number_2->value = $field['emerg_number_2'];
    $student->field_student_emerg_contact_2->value = $field['emerg_contact_2'];
    $student->field_student_emerg_2_rel->value = $field['emerg_2_rel'];
    $student->field_student_auth_for_pickup->value = $field['auth_pickup'];
    $student->field_student_allergies->value = $field['allergies'];
    $student->field_student_parent_uid->target_id = $user->uid->value;
    if ($student->field_student_status->value == 'Attending 16/17' || $student->field_student_status->value == 'Attending 17/18') {
      $student->field_student_status->value = 'Attending 17/18';
    }
    else {
      $student->field_student_status->value = 'Pending';
    }
    
    $student->save();
    
    //Create new application node every time
    
    $application = Node::create([
      'type'        	=> 'application',
      'title'       	=> $field['first'] . ' ' . $field['last'] . ' Application ' . date('Y'),
    ]);
    $application->field_app_list_allergies->value = $field['allergies'];
    $application->field_app_capable->value = $field['capable'];
    $application->field_app_epi_pen->value = $field['epi_pen'];
    $application->field_app_inhaler->value = $field['inhaler'];
    $application->field_app_academic_release->value = $field['academic_waiver'];
    $application->field_app_media_release_waiver->value = $field['media_waiver'];
    $application->field_app_medical_waiver->value = $field['medical_waiver'];
    $application->field_app_code_of_conduct_waiver->value = $field['conduct_waiver'];
    $application->field_app_liability_release->value = $field['liability_release'];
    $application->field_app_lending_policy->value = $field['lending_policy'];
    $application->field_app_comments->value = $field['comments'];
    $application->field_student_id->target_id = $student->nid->value;
    $application->save();
    
    $studentfirst = $field['first'];
    $studentname = $field['first'] . ' ' . $field['last'];
    $school = $field['school'];
    
    if ($student->field_student_status->value == 'Attending 17/18') {
      $mailtype = 'ReturningStudent';
      drupal_set_message($studentname . ' is now enrolled in the Sistema Toronto 2017/18 program.');
    }
    else {
      $mailtype = 'NewStudent';
      drupal_set_message('You have submitted an application for ' . $studentname . '. We will be in touch by the end of April to let you know the status of your application.');
    }
    //Send email
    // drupal_set_message('Nid:' . $student->nid->value);
    
    SistemaMail($mailtype, $studentfirst, $studentname, $school);
    
    //Redirect to front page
    
    $response = new RedirectResponse('/');
    $response->send();
    
  }
}

function phoneHelper($phone) {
  if (strlen($phone) > 0 && !preg_match('/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/i', $phone)) {
    return TRUE;
  }
}

function dobHelper($dob) {
  if (strlen($dob) > 0 && !preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $dob)) {
    return TRUE;
  }
}

function gradeHelper($dob) {
  if (strlen($dob) > 0 && !preg_match('/^[0-4]{1}$/i', $dob)) {
    return TRUE;
  }
}


function SistemaMail($mailtype, $studentfirst, $studentname, $school) {
  
  $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
  $userfirst = $user->get('field_user_first')->value;
  
  $to = \Drupal::currentUser()->getEmail();
  
  $from = \Drupal::config('system.site')->get('mail');
  
  if ($mailtype == 'NewStudent') {
  
    $subject = t('You successfully submitted a Sistema Toronto application for @studentname', array('@studentname' => $studentname));
  
    $body = t('<p>Thank you for submitting a Sistema Toronto 2017/18 registration for:</p>
       <p>@studentname</p>
       <p>Though we would love to accept all new applicants, space in our program is limited. The Sistema Toronto Academy will, at its discretion, accept a number of students from Grades One to Three in 2017-18. Students will be chosen by school recommendation and/or witnessed lottery in consultation with school principal and teachers. When all places are filled, the remaining names will be placed on a waiting list in their lottery order. The list of names will not be maintained after November 1, 2017. Students/parents will be notified if their child is accepted or wait listed by May 26, 2017.</p>
       <p>Thank-you,</p>
       <p>The Sistema Toronto Team</p>', array(
      '@studentfirst' => $studentfirst,
      '@studentname' => $studentname,
      '@userfirst' => $userfirst,
      '@school' => $school,
    ));
  }
  
  if ($mailtype == 'ReturningStudent') {
  
    $subject = t('You successfully submitted a Sistema Toronto renewal for @studentname', array('@studentname' => $studentname));
  
    $body = t(
      '<p>Thank you for submitting a Sistema Toronto 2017/18 renewal for:</p>
       <p>@studentname</p>
       <p>Our office will be in touch in June 2017 to confirm September program start dates. If you have any questions or concerns, please contact info@sistema-toronto.ca.</p>
       <p>Thank-you,</p>
       <p>The Sistema Toronto Team<p>', array(
      '@studentname' => $studentname,
      '@userfirst' => $userfirst,
      '@school' => $school,
    ));
  }
  
  simple_mail_send($from, $to, $subject, $body);
  
  $message = t('An email notification has been sent to @email.', array('@email' => $to));
  drupal_set_message($message);
  \Drupal::logger('sistema_custom')->notice($message);
  
}
?>
