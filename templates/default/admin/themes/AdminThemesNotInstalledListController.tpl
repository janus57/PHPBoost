# INCLUDE UPLOAD_FORM #
<form action="{REWRITED_SCRIPT}" method="post">
	{@themes.not_installed}
	<table>
		# IF C_THEME_INSTALL #
		<thead>
			<tr> 
				<th>
					{@themes.name}
				</th>
				<th>
					{@themes.description}
				</th>
				<th>
					{@themes.authorisations}
				</th>
				<th>
					{@themes.activated}
				</th>
			</tr>
		</thead>
		<tbody>
			<tr> 
				<td colspan="4">
					# INCLUDE MSG #	
				</td>
			</tr>
			# ELSE #
			<tr>
				<td colspan="4">
					<span class="text_strong">{@themes.add.not_theme}</span>
				</td>
			</tr>
			# ENDIF #
			# START themes_not_installed #
				<tr> 	
					<td>					
						<span id="theme-{themes_not_installed.ID}"></span>
						<span class="text_strong">{themes_not_installed.NAME}</span> <span class="text_italic">({themes_not_installed.VERSION})</span>
						<br /><br />
						# IF themes_not_installed.C_PICTURES #
							<a href="{themes_not_installed.MAIN_PICTURE}" rel="lightbox[{themes_not_installed.ID}]" title="{themes_not_installed.NAME}">
								<img src="{themes_not_installed.MAIN_PICTURE}" alt="{themes_not_installed.NAME}" style="vertical-align:top; max-height:180px;" />
								<br/>
								{@themes.view_real_preview}
							</a>
							# START themes_not_installed.pictures #
								<a href="{themes_not_installed.pictures.URL}" rel="lightbox[{themes_not_installed.ID}]" title="{themes_not_installed.NAME}"></a>
							# END themes_not_installed.pictures #
						# ENDIF #
						
					</td>
					<td>
						<div id="desc_explain{themes_not_installed.ID}">
							<span class="text_strong">{@themes.author}:</span> 
							<a href="mailto:{themes_not_installed.AUTHOR_EMAIL}">
								{themes_not_installed.AUTHOR_NAME}
							</a>
							# IF themes_not_installed.C_WEBSITE # 
							<a href="{themes_not_installed.AUTHOR_WEBSITE}" class="small-button">Web</a>
							# ENDIF #
							<br />
							<span class="text_strong">{@themes.description}:</span> {themes_not_installed.DESCRIPTION}<br />
							<span class="text_strong">{@themes.compatibility}:</span> PHPBoost {themes_not_installed.COMPATIBILITY}<br />
							<span class="text_strong">{@themes.html_version}:</span> {themes_not_installed.HTML_VERSION}<br />
							<span class="text_strong">{@themes.css_version}:</span> {themes_not_installed.CSS_VERSION}<br />
							<span class="text_strong">{@themes.main_color}:</span> {themes_not_installed.MAIN_COLOR}<br />
							<span class="text_strong">{@themes.width}:</span> {themes_not_installed.WIDTH}<br />
						</div>
					</td>
					<td>
						<div id="authorizations_explain-{themes_not_installed.ID}">
							{themes_not_installed.AUTHORIZATIONS}
						</div>
					</td>
					<td>
						<label><input type="radio" name="activated-{themes_not_installed.ID}" value="1" checked="checked"> {@themes.yes}</label>
						<label><input type="radio" name="activated-{themes_not_installed.ID}" value="0"> {@themes.no}</label>
						<br /><br />
						<input type="submit" name="add-{themes_not_installed.ID}" value="{L_ADD}" class="submit"/>
					</td>
				</tr>						
			# END themes_not_installed #
		</tbody>
	</table>
	
	<fieldset class="fieldset_submit">
		<legend>{L_SUBMIT}</legend>
		<input type="hidden" name="token" value="{TOKEN}">
	</fieldset>
</form>