function setHideShowPlugin($element) {
	$($element).hideShowPassword({
		  // Make the password visible right away.
		  show: false,
		  // Create the toggle goodness.
		  innerToggle: true,
		  // Give the toggle a custom class so we can style it
		  // separately from the previous example.
		  toggleClass: 'my-toggle-class',
		  // Don't show the toggle until the input triggers
		  // the 'focus' event.
		  hideToggleUntil: 'focus',
		  // Enable touch support for toggle.
		  touchSupport: Modernizr.touch
	});
}	
