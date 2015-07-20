<?php
  namespace controllers;
  
  class TranslateFormController extends SecureController {
    public function __construct(\TemplateEngine &$engine) {
      parent::__construct('TranslateForm', $engine, false);
    }
    
    public function load() {
      parent::load();
      
      $model = $this->getModel();
      if ($model === null) {
        throw new \exceptions\NotImplementedException(__METHOD__);
      }
      
      $this->bind($model);
    }
    
    private function bind(\models\TranslateFormModel& $model) {
      $this->_engine->assign('inventedLanguages', $model->getLanguages());
      $this->_engine->assign('wordClasses',       $model->getWordClasses());
      $this->_engine->assign('wordGenders',       $model->getWordGenders());
      $this->_engine->assign('reviewID',          $model->getReviewID());
      $this->_engine->assign('groups',            $model->getGroups());
        
      $original =& $model->getOriginal();
      
      // Check whether the current user is permitted to perform changes tot his translation entry.
      if (! \auth\Credentials::permitted(new \auth\TranslationAccessRequest($original->id))) {
        $original->disassociate();
      }
      
      $this->_engine->assign('id',                $original->id);
      $this->_engine->assign('senseID',           $original->senseID);
      $this->_engine->assign('orig_language',     $original->language);
      $this->_engine->assign('orig_word',         $original->word);
      $this->_engine->assign('orig_translation',  $original->translation);
      $this->_engine->assign('orig_comments',     $original->comments);
      $this->_engine->assign('orig_source',       $original->source);
      $this->_engine->assign('orig_etymology',    $original->etymology);
      $this->_engine->assign('orig_tengwar',      $original->tengwar);
      $this->_engine->assign('orig_type',         $original->type);
      $this->_engine->assign('orig_gender',       $original->gender);
      $this->_engine->assign('orig_phonetic',     $original->phonetic);
      $this->_engine->assign('orig_group',        $original->group->id);
      $this->_engine->assign('orig_indexes',      json_encode($model->getIndexes()));
      $this->_engine->assign('operation',        ($original->id > 0 ? 'Edit' : 'Add'));
      $this->_engine->assign('justification',     $model->getJustification());
      $this->_engine->assign('isAdmin',           $model->isAdministrator());

      if ($model->getReviewID() !== null) {
        if ($model->isAdministrator()) {
          $mode = 'review';
        } else if (! $model->isResubmission()) {
          $mode = 'edit';
        }
      } else {
        $mode = 'create';
      }

      $this->_engine->assign('mode', $mode);
    }
  }
