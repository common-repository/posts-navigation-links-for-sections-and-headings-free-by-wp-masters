<?php
	if(empty($founded_titles)) {
		return;
	}
?>
<div class="post-navigation-links" style="background: <?php if(isset($this->settings['background'])) {echo esc_attr($this->settings['background']);} ?>">
    <div class="navigation-links-title" style="color: <?php if(isset($this->settings['title_color'])) {echo esc_attr($this->settings['title_color']);} ?>;border-bottom: 1px solid <?php if(isset($this->settings['border_color'])) {echo esc_attr($this->settings['border_color']);} ?>;"><?php if(isset($this->settings['title_links'])) {echo esc_html($this->settings['title_links']);} ?></div>
	<ul>
		<?php
			foreach($founded_titles as $url => $title) { ?>
				<li><a href="#<?php echo esc_attr($url); ?>"><?php echo esc_html($title); ?></a></li>
			<?php }
		?>
	</ul>
</div>