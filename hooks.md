# FILTERS
- apply_filters('sim_email_footer_url', [
	'url'   => SITEURL,
	'text'  => SITEURL
])
- apply_filters('sim_email_footer', $this->footer, $this->message);

# Actions
- 