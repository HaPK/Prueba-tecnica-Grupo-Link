<?php
/**
 * @file
 * Content \Drupal\example-module\form\TestForm
 */
namespace Drupal\example-module\form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class TestForm extends FormBase {

    public function getFormId() {
        return 'TestForm';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {

        $form['nombre'] = array(
            '#type' => 'textfield',
            '#title' => t('Nombre'),
            '#required' => TRUE,
        );
        $form['identificacion'] = array(
            '#type' => 'textfield',
            '#title' => t('Identificación'),
            '#required' => TRUE,
        );
        $form['fecha_nacimiento'] = array(
            '#type' => 'date',
            '#title' => t('Fecha de nacimiento'),
            '#default_value' => '2020-01-13'
            '#required' => TRUE,
        );
        $form['cargo'] = array(
            '#type' => 'checkboxes',
            '#title' => t('Cargo'),
            '#options' => array(
                'Administrador' => $this->t('administrador'),
                'Webmaster' => $this->t('webmaster'),
                'Desarrollador' => $this->t('desarrollador')
            ),
            '#required' => TRUE,
        );
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit'
            '#value' => $this->t('Guardar'),
            '#button_type' => 'primary',
        );

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        if (empty($form_state->getValue('nombre'))){
            $form_state->setErrorByName('nombre',$this->t('Es necesario introducir un nombre'));
        }
        if (empty($form_state->getValue('identificacion'))){
            $form_state->setErrorByName('identificacion',$this->t('Es necesario introducir un número de identificación'));
        }
        if (empty($form_state->getValue('fecha_nacimiento'))){
            $form_state->setErrorByName('nombre',$this->t('Es necesario introducir una fecha en el formato AAAA-MM-DD'));
        }
        if (empty($form_state->getValue('cargo'))){
            $form_state->setErrorByName('nombre',$this->t('Por favor seleccione al menos un cargo'));
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state){
        $values = array(
            'nombre' => $form_state->getValue('nombre'),
            'identificacion' => $form_state->getValue('identificacion'),
            'fecha_nacimiento' => $form_state->getValue('fecha_nacimiento'),
            'cargo' => $form_state->getValue('cargo'),
        );

        $estado = 0;
        if(strcmp($values['cargo'], 'administrador')==0){
            $estado = 1;
        }
        $values['estado'] = $estado;

        $table = 'example-users';
        
        \Drupal::database()->insert($table)
        ->fields(array(
            'nombre' => $values['nombre'],
            'identificacion' => $values['identificacion'],
            'fecha_nacimiento' => $values['fecha_nacimiento'],
            'cargo' => $values['cargo'],
            'estado' => $values['estado'],
        ))
        ->execute();
        
        foreach($form_state->getValues() as $key => $value) {
            drupal_set_message($key . ': ' . $value);
        }

        drupal_set_message('Guardado correctamente.', 'status');
        
    }
}