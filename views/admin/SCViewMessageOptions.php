<div class="TWD_global_options">
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab" href="edit.php?post_type=contact&page=cont_option&tab=cont_option">Global options</a>
		<a class="nav-tab nav-tab-active" href="edit.php?post_type=contact&page=cont_option&tab=mess_option">Message Options</a>
		<a class="nav-tab " href="edit.php?post_type=contact&page=cont_option&tab=custom_css">Custom CSS</a>
	</h2>

	<form action="options.php" method="post" id="adminForm" name="adminForm" class="form-table">
		<?php settings_fields('mess_option'); ?>
		<?php do_settings_sections('mess_option'); ?>
		<table style="min-width:500px" class="paramlist" cellspacing="1">
			<tr>
				<?php
				$check = "";
					if (esc_attr(get_option('enable_message')) == 1){
						$check = ' checked="checked" ';
					}
				?>
				<th scope="row"><?php echo " Send Message";?></th>
				<td class="paramlist_value">
					<input type="radio" name="enable_message" id="enable_message0" value="0"  <?php if($check==""):?> checked="checked" <?php endif;?>/>
						<label for="enable_message0"><?php echo 'Off';?></label>
					<input type="radio" name="enable_message" id="enable_message1" value="1" <?php echo $check; ?>   />
						<label for="enable_message1"><?php echo 'On';?></label>
					<p class="paramlist_descriptions"><?php echo 'Choose whether to display the Contact form in the single page or not.'; ?></p>		
				</td>
			</tr>
			<tr>
				<?php
				$check = "";
					if (esc_attr(get_option('show_name')) == 1){
						$check = ' checked="checked" ';
					}
				?>
				<th scope="row"><?php echo '"Name" Field'; ?></th>
				<td class="paramlist_value">
					<input type="radio" name="show_name" id="show_name0" value="0"  <?php if($check==""):?> checked="checked" <?php endif;?> />
						<label for="show_name0"><?php echo 'Off';?></label>
					<input type="radio" name="show_name" id="show_name1" value="1" <?php echo $check; ?>   />
						<label for="show_name1"><?php echo 'On';?></label>
					<p class="paramlist_descriptions"><?php echo 'Choose whether to display the field of "Name" in contact form of the single page or not.'; ?></p>			
				</td>
			</tr>
			<tr>
				<?php
				$check = "";
					if (esc_attr(get_option('show_phone')) == 1){
						$check = ' checked="checked" ';
					}
				?>
				<th scope="row"><?php echo '"Phone" Field'; ?></th>
				<td class="paramlist_value">
					<input type="radio" name="show_phone" id="show_phone0" value="0"  <?php if($check==""):?> checked="checked" <?php endif;?> />
						<label for="show_phone0"><?php echo 'Off';?></label>
					<input type="radio" name="show_phone" id="show_phone1" value="1" <?php echo $check; ?>   />
						<label for="show_phone1"><?php echo 'On';?></label>
					<p class="paramlist_descriptions"><?php echo 'Choose whether to display the field of "Phone" in contact form of the single page or not.'; ?></p>	
				</td>
			</tr>
			<tr>
				<?php
				$check = "";
					if (esc_attr(get_option('show_email')) == 1){
						$check = ' checked="checked" ';
					}
				?>
				<th scope="row"><?php echo '"Email" Field'; ?></th>
				<td class="paramlist_value">
					<input type="radio" name="show_email" id="show_email0" value="0"  <?php if($check==""):?> checked="checked" <?php endif;?> />
						<label for="show_email0"><?php echo 'Off';?></label>
					<input type="radio" name="show_email" id="show_email1" value="1" <?php echo $check; ?>   />
						<label for="show_email1"><?php echo 'On';?></label>
					<p class="paramlist_descriptions"><?php echo 'Choose whether to display the field of "Email" in contact form of the single page or not.'; ?></p>	
				</td>
			</tr>
			<tr>
				<?php
				$check = "";
					if (esc_attr(get_option('show_cont_pref')) == 1){
						$check = ' checked="checked" ';
					}
				?>
				<th scope="row"><?php echo '"Contact Preferences" Field';?></th>
				<td class="paramlist_value">
					<input type="radio" name="show_cont_pref" id="show_cont_pref0" value="0"  <?php if($check==""):?> checked="checked" <?php endif;?> />
						<label for="show_cont_pref0"><?php echo 'Off';?></label>
					<input type="radio" name="show_cont_pref" id="show_cont_pref1" value="1" <?php echo $check; ?>   />
						<label for="show_cont_pref1"><?php echo 'On';?></label>
					<p class="paramlist_descriptions"><?php echo 'Choose whether to display the field of "Contact Preferences" in contact form of the single page or not.'; ?></p>	
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
</div>