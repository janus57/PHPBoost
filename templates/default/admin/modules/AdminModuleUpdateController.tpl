# INCLUDE MSG_WARNING #
# INCLUDE MSG_SUCCESS #
# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	<input type="hidden" name="token" value="{TOKEN}">
	<section id="upgrade-modules-container" class="admin-elements-container modules-elements-container upgrade-elements-container">
		<header class="legend">{@modules.updates_available}</header>
		# IF C_UPDATES #
			<div class="cell-flex cell-flex-3">
				# START modules_upgradable #
					<article class="cell admin-element module-element upgrade-element">
						<header class="cell-title">
							<div class="admin-element-menu-container">
								# IF modules_upgradable.C_COMPATIBLE #
									<button type="submit" class="submit admin-element-menu-title" name="upgrade-{modules_upgradable.ID}" value="true">{@modules.upgrade_module}</button>
								# ELSE #
									<span class="admin-element-menu-title">${LangLoader::get_message('not_compatible', 'admin-common')}</span>
								# ENDIF #
							</div>
							# IF C_MORE_THAN_ONE_MODULE_AVAILABLE #
								# IF modules_upgradable.C_COMPATIBLE #
									<div class="form-field form-field-checkbox multiple-checkbox-container mini-checkbox">
										<label class="checkbox" for="multiple-checkbox-{modules_upgradable.MODULE_NUMBER}">
											<input type="checkbox" class="multiple-checkbox upgrade-checkbox" id="multiple-checkbox-{modules_upgradable.MODULE_NUMBER}" name="upgrade-checkbox-{modules_upgradable.MODULE_NUMBER}"/>
											<span>&nbsp;</span>
										</label>
									</div>
								# ENDIF #
							# ENDIF #
							<h2 class="upgrade-module-name">{modules_upgradable.NAME}<em> ({modules_upgradable.VERSION})</em></h2>
						</header>
						<div class="cell-list">
							<ul>
								<li>
									<img class="valign-middle" src="{PATH_TO_ROOT}/{modules_upgradable.ICON}/{modules_upgradable.ICON}.png" alt="{modules_upgradable.NAME}" />
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('author', 'admin-common')} :</span>
									<span># IF modules_upgradable.C_AUTHOR_EMAIL #<a href="mailto:{modules_upgradable.AUTHOR_EMAIL}">{modules_upgradable.AUTHOR}</a># ELSE #{modules_upgradable.AUTHOR}# ENDIF # # IF modules_upgradable.C_AUTHOR_WEBSITE #<a href="{modules_upgradable.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #</span>
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('form.date.creation', 'common')} :</span>
									{modules_upgradable.CREATION_DATE}
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('last_update', 'admin')} :</span>
									{modules_upgradable.LAST_UPDATE}
								</li>
								<li>
									<span class="text-strong">${LangLoader::get_message('description', 'main')} :</span>
									{modules_upgradable.DESCRIPTION}
								</li>
								<li class="li-stretch">
									<span class="text-strong">${LangLoader::get_message('compatibility', 'admin-common')} :</span>
									<span# IF NOT modules_upgradable.C_COMPATIBLE # class="not-compatible"# ENDIF#>PHPBoost {modules_upgradable.COMPATIBILITY}</span>
								</li>
							</ul>
						</div>

						<footer></footer>
					</article>
				# END modules_upgradable #
			</div>
		# ELSE #
			<div class="content">
				<div class="message-helper notice message-helper-small">${LangLoader::get_message('no_item_now', 'common')}</div>
			</div>
		# ENDIF #
		<footer></footer>
	</section>
	# IF C_MORE_THAN_ONE_MODULE_AVAILABLE #
	<div class="multiple-select-menu-container admin-element-menu-title mini-checkbox">
		<div class="form-field form-field-checkbox select-all-checkbox">
			<label class="checkbox" for="upgrade-all-checkbox">
				<input type="checkbox" class="check-all" id="upgrade-all-checkbox" name="upgrade-all-checkbox" onclick="multiple_checkbox_check(this.checked, {MODULES_NUMBER});" aria-label="{@modules.select_all_modules}" />
				<span>&nbsp;</span>
			</label>
		</div>
		<button type="submit" name="upgrade-selected-modules" value="true" class="submit select-all-button">${LangLoader::get_message('multiple.upgrade_selection', 'admin-common')}</button>
	</div>
	# ENDIF #
</form>
<script>
	jQuery('.admin-element-auth').opensubmenu({
		osmTarget: '.admin-element-auth-container',
		osmCloseExcept: '.admin-element-auth-content *',
		osmCloseButton: '.admin-element-auth-close i',
	});
</script>
