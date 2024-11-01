<div class="wrap">

	<div id="icon-themes" class="icon32"><br></div>
	<h2>Wordpress Theme Options</h2>
	
	<div id="message" style="display:none;" class="updated"><p>Successfully saved.</p></div>

	<form method="post" action="options.php">
		<h3>General Settings</h3>
		These settings affect the general look of your theme.
		
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Color Scheme</th>
				<td>				
					<div class="mg-color-scheme-item" style="float: left; margin-right: 14px; margin-bottom: 18px;">
						<input  type="radio" <?php if ($options['color-scheme'] == "default") echo "checked=\"checked\""; ?> name="wpoptions-options[color-scheme]" id="wpoptions-color-scheme-default" value="default" />
						
						
			
						
						<label for="wpoptions-color-scheme-default" style="margin-top: 4px; float: left; clear: both;">
							<img src="<?php echo $plugin_dir; ?>includes/colors/default/preview.png" /><br />
							<span class="description" style="margin-top: 8px; float: left;">Default</span>
						</label>
					</div>

					<div class="mg-color-scheme-item" style="float: left; margin-right: 14px; margin-bottom: 18px;">
						<input  type="radio" <?php if ($options['color-scheme'] == "dark") echo "checked=\"checked\""; ?> name="wpoptions-options[color-scheme]" id="wpoptions-color-scheme-dark" value="dark" />
						<label for="wpoptions-color-scheme-dark" style="margin-top: 4px; float: left; clear: both;">
							<img src="<?php echo $plugin_dir; ?>includes/colors/dark/preview.png" /><br />
							<span class="description" style="margin-top: 8px; float: left;">dark</span>
						</label>
					</div>
			
					<div class="mg-color-scheme-item" style="float: left; margin-right: 14px; margin-bottom: 18px;">
						<input  <?php if ($options['color-scheme'] == "light") echo "checked=\"checked\""; ?> type="radio" name="wpoptions-options[color-scheme]" id="wpoptions-color-scheme-light" value="light" />
						<label for="wpoptions-color-scheme-light" style="margin-top: 4px; float: left; clear: both;">
							<img src="<?php echo $plugin_dir; ?>includes/colors/light/preview.png" /><br />

							<span class="description" style="margin-top: 8px; float: left;">light</span>
						</label>
					</div>
					
					<br class="clear" />
					<span class="description">Browse to your home page to see the new color scheme in action.</span>	
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Title Font</th>
				<td>       
								
					<select id="title-font" name="wpoptions[title-font]">
						<option value="">Select</option>
						<?php
							foreach ($fonts as $key => $val)
							{
								echo "<option  value=\"{$key}\" ";
								if ($options["title-font"] == $key) echo "selected=\"selected\"";
								echo ">{$val['name']}</option>";	
							}
						?>	
					</select>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Content Font</th>
				<td>        
					<select id="content-font" name="wpoptions[content-font]">
						<option value="">Select</option>
						<?php
							foreach ($fonts as $key => $val)
							{
								echo "<option  value=\"{$key}\" ";
								if ($options["content-font"] == $key) echo "selected=\"selected\"";
								echo ">{$val['name']}</option>";									
							}
						?>						
					</select>
				</td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Custom CSS</th>
				<td>		
					<textarea rows="5" class="large-text code" id="custom-css" name="wpoptions-options[custom-css]"><?=$options['custom-css']?></textarea><br />
					<span class="description">Custom stylesheets are included in the head section after all the theme stylesheets are loaded.</span>

				</td>
			</tr>
			

		</table>		
		
		<p class="submit">
			<div style="float:left"><input name="Submit" id="submit" type="button" class="button-primary" value="Save Changes" /> </div>
			<div id="loader" style="float:left; display:none; margin:5px 0 0 10px;"><img src="<?=$plugin_dir?>/includes/4.gif" /></div>
		</p >
	</form>
	
	
</div>

