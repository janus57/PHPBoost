# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post" class="fieldset-content">
	<input type="hidden" name="token" value="{TOKEN}">
	# INCLUDE MSG #
	<section id="not-installed-themes-container" class="admin-elements-container themes-elements-container not-installed-elements-container">
		<header class="legend">{@themes.available_themes}</header>
		# IF C_THEME_AVAILABLE #
		<div class="cell-flex cell-flex-3">
			# START themes_not_installed #
			<article class="cell admin-element theme-element not-installed-element# IF NOT themes_not_installed.C_COMPATIBLE # not-compatible# ENDIF #">
				<header class="cell-title">
					<div class="admin-element-menu-container">
						# IF themes_not_installed.C_COMPATIBLE #
							<button type="submit" class="submit admin-element-menu-title" name="add-{themes_not_installed.ID}" value="true">${LangLoader::get_message('install', 'admin-common')}</button>
						# ELSE #
							<span class="admin-element-menu-title">${LangLoader::get_message('not_compatible', 'admin-common')}</span>
						# ENDIF #
					</div>
					# IF C_MORE_THAN_ONE_THEME_AVAILABLE #
						# IF themes_not_installed.C_COMPATIBLE #
							<div class="form-field form-field-checkbox multiple-checkbox-container mini-checkbox">
								<label class="checkbox" for="multiple-checkbox-{themes_not_installed.THEME_NUMBER}">
									<input type="checkbox" class="multiple-checkbox add-checkbox" id="multiple-checkbox-{themes_not_installed.THEME_NUMBER}" name="add-checkbox-{themes_not_installed.THEME_NUMBER}"/>
									<span>&nbsp;</span>
								</label>
							</div>
						# ENDIF #
					# ENDIF #

					<h2 class="not-installed-theme-name">{themes_not_installed.NAME}<em> ({themes_not_installed.VERSION})</em></h2>
				</header>

				<div class="cell-body">
					<div class="cell-thumbnail" >
						# IF themes_not_installed.C_PICTURES #
							<img src="{themes_not_installed.MAIN_PICTURE}" alt="{themes_not_installed.NAME}" />
							<a class="cell-thumbnail-caption" href="{themes_not_installed.MAIN_PICTURE}" data-lightbox="{themes_not_installed.ID}" data-rel="lightcase:collection-{themes_not_installed.ID}">
								{@themes.view_real_preview}
							</a>
							# START themes_not_installed.pictures #
								<a href="{themes_not_installed.pictures.URL}" data-lightbox="{themes_not_installed.ID}" data-rel="lightcase:collection-{themes_not_installed.ID}" aria-label="{themes_not_installed.NAME}"></a>
							# END themes_not_installed.pictures #
						# ENDIF #
					</div>
				</div>
				<div class="cell-list">
					<ul>
						<li class="li-stretch">
							<span class="text-strong">
								${LangLoader::get_message('author', 'admin-common')} :
							</span>
							<span>
								# IF themes_not_installed.C_AUTHOR_EMAIL #<a href="mailto:{themes_not_installed.AUTHOR_EMAIL}">{themes_not_installed.AUTHOR}</a># ELSE #{themes_not_installed.AUTHOR}# ENDIF # # IF themes_not_installed.C_AUTHOR_WEBSITE #<a href="{themes_not_installed.AUTHOR_WEBSITE}" class="basic-button smaller">Web</a># ENDIF #<br />
							</span>
						</li>
						<li class="li-stretch">
							<span class="text-strong">${LangLoader::get_message('form.date.creation', 'common')} :</span>
							{themes_not_installed.CREATION_DATE}
						</li>
						<li class="li-stretch">
							<span class="text-strong">${LangLoader::get_message('last_update', 'admin')} :</span>
							{themes_not_installed.LAST_UPDATE}
						</li>
						<li>
							<span class="text-strong">${LangLoader::get_message('description', 'main')} :</span>
							{themes_not_installed.DESCRIPTION}
						</li>
						<li class="li-stretch">
							<span class="text-strong">${LangLoader::get_message('compatibility', 'admin-common')} :</span>
							<span# IF NOT themes_not_installed.C_COMPATIBLE # class="not-compatible"# ENDIF #>PHPBoost {themes_not_installed.COMPATIBILITY}</span>
						</li>
						<li class="li-stretch">
							<span class="text-strong">{@themes.html_version} :</span>
							{themes_not_installed.HTML_VERSION}
						</li>
						<li class="li-stretch">
							<span class="text-strong">{@themes.css_version} :</span>
							{themes_not_installed.CSS_VERSION}
						</li>
						<li class="li-stretch">
							<span class="text-strong">{@themes.main_color} :</span>
							{themes_not_installed.MAIN_COLOR}
						</li>
						<li class="li-stretch">
							<span class="text-strong">{@themes.width} :</span>
							{themes_not_installed.WIDTH}
						</li>
					</ul>
				</div>

				<footer class="cell-footer">
					# IF themes_not_installed.C_COMPATIBLE #
						<div class="admin-element-auth-container">
							<a href="" class="admin-element-auth" aria-label="${LangLoader::get_message('members.config.authorization', 'admin-user-common')}"><i class="fa fa-user-shield" aria-hidden="true"></i></a>
							<div class="admin-element-auth-content">
								{themes_not_installed.AUTHORIZATIONS}
								<a href="#" class="admin-element-auth-close" aria-label="${LangLoader::get_message('close', 'main')}"><i class="fa fa-times" aria-hidden="true"></i></a>
							</div>
						</div>
					# ENDIF #
				</footer>
			</article>
			# END themes_not_installed #
		</div>
		# ELSE #
		<div class="content">
			<div class="message-helper notice message-helper-small">${LangLoader::get_message('no_item_now', 'common')}</div>
		</div>
		# ENDIF #
		<footer></footer>
	</section>
	# IF C_MORE_THAN_ONE_THEME_AVAILABLE #
	<div class="multiple-select-menu-container admin-element-menu-title">
		<div class="form-field form-field-checkbox select-all-checkbox mini-checkbox">
			<label class="checkbox" for="add-all-checkbox">
				<input type="checkbox" class="check-all" id="add-all-checkbox" name="add-all-checkbox" onclick="multiple_checkbox_check(this.checked, {THEMES_NUMBER});" aria-label="{@themes.select_all_themes}" />
				<span>&nbsp;</span>
			</label>
		</div>
		<button type="submit" name="add-selected-themes" value="true" class="submit select-all-button">${LangLoader::get_message('multiple.install_selection', 'admin-common')}</button>
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
