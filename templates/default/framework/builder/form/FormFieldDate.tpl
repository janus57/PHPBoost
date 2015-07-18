# IF C_HOUR #
<script>
<!--
jQuery(document).ready(function() {
	if (jQuery("#${escape(HTML_ID)}_hours").length)
	{
		jQuery("#${escape(HTML_ID)}_hours").blur(function() {
			HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").enableValidationMessage();
			HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").liveValidate();
		});
	}
	if (jQuery("#${escape(HTML_ID)}_minutes").length)
	{
		jQuery("#${escape(HTML_ID)}_minutes").blur(function() {
			HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").enableValidationMessage();
			HTMLForms.get("${escape(FORM_ID)}").getField("${escape(ID)}").liveValidate();
		});
	}
});
-->
</script>
# ENDIF #
<div id="${escape(HTML_ID)}_field" # IF C_HIDDEN # style="display:none;" # ENDIF # class="form-element form-element-date # IF C_REQUIRED_AND_HAS_VALUE # constraint-status-right # ENDIF #">
	<label for="${escape(HTML_ID)}">
		{LABEL}
		# IF DESCRIPTION #<span class="field-description">{DESCRIPTION}</span> # ENDIF #
	</label>
	<div id="onblurContainerResponse${escape(HTML_ID)}" class="form-field# IF C_HAS_FORM_FIELD_CLASS # {FORM_FIELD_CLASS}# ENDIF # picture-status-constraint# IF C_REQUIRED # field-required # ENDIF #">
		{CALENDAR}
		<span class="text-status-constraint" style="display:none" id="onblurMessageResponse${escape(HTML_ID)}"></span>
		# IF C_HOUR #
		{L_AT}
		<input type="number" min="0" max="23" id="${escape(HTML_ID)}_hours" name="${escape(HTML_ID)}_hours" value="{HOURS}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF #/> {L_H}
		<input type="number" min="0" max="59" id="${escape(HTML_ID)}_minutes" name="${escape(HTML_ID)}_minutes" value="{MINUTES}" # IF C_DISABLED # disabled="disabled" # ENDIF # # IF C_READONLY # readonly="readonly" # ENDIF #/>
		# ENDIF #
	</div>
</div>

# INCLUDE ADD_FIELD_JS #
