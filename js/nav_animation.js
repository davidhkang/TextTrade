var nav = document.querySelector('.main-nav');

window.addEventListener('scroll', function(){
	if(window.pageYOffset > 100){
		nav.classList = 'main-nav small';
	} else {
		nav.classList ='main-nav';
	}
})