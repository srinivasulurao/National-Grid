<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>">
  <?php if($this->data['survey_link']): ?>
  <p>
      Please complete the <a id="customerFeedbackSurvey" class="link_button" href="<?php echo $this->data['survey_link']; ?>" target="_blank">Feedback Resolution Survey</a>
  </p>
<?php endif; ?>
</div>
