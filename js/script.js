function greeting(){
	const d = new Date();
	let hour = d.getHours();
	//console.log(hour);
	let myGreeting ="";

	if(console < 18){
		myGreeting = "Bonjour";
	}else{
		myGreeting = "Bonsoir";
	}  
    $(".greeting").html(myGreeting);
}


$( document ).ready(function() {
    console.log( "ready!" );
    greeting();
});
