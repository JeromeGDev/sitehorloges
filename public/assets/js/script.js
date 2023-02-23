/* Fermeture d'un élément au clix sur la croix */
// surveille le bouton ayant l'ID "closerBtn" et ajoute l'écouteur d'évènement qui ferme l'élément concerné par le clic
const closerBtn = document.querySelector( "#closerBtn" ); // retourne un tableau de valeurs
closerBtn.addEventListener( "click", closingMenu );

//Surveillance du menu de navigation
const navMenu = document.querySelector( "#navMenu" ); // retourne un tableau de valeurs

function closingMenu(e) {
    e.preventDefault();
    e.stopPropagation();
    if ( this.classList.contains( "hideNav" ) ) {
        this.classList.toggle( hideNav );
    }
    
}


/* ============================= Création de boutons de navigation =============================  */
// récupère l'élément ul du menu de navigation
const navList = document.querySelector( "#navList" );

//Liste de liens à insérer
let navlinks = {
    "baliseLi" : "li",
    "classeLi": "navMenuBlock__navList__menuitem",
    "baliseA": "a",
    "attributeA" : "href",
    "classA": [ "navMenuBlock__navList__menuitem__link", "navBtn" ],
    "linksA": [ "#" ],
    "attributeTitle" : [ "Titre de lien" ],
    "linksTitle": ["#"]
}

//Création des liens de menus
function createNavlinks(){
    navlinks.key.forEach( () => {
        console.log( "Hello you!" );

        const newMenuitem = document.createElement( navlinks.baliseLi ).setAttribute( "class", navlinks.classeLi );
        console.log( "navList", navList );

        const newMenuLink = document.createElement( navlinks.baliseA );
        console.log( "navList", navList );

        navlinks.classA.forEach( () => {
            newMenuLink.setAttribute( "class", navlinks.classA );
        } )
        navlinks.linksA.forEach( () => {
            newMenuLink.setAttribute(navlinks.attributeA, value).setAttribute( "href", navlinks.linksA );
        } );

        navlinks.attibuteTitlte.forEach( () => {
            newMenuLink.setAttribute(navlinks.attributeA, value).setAttribute( "title", navlinks.attibuteTitlte );
        } );

        navlinks.linksA.forEach( () => {
            const textLink = document.createTextNode(navLinks.attributeTitle)
            newMenuLink.append(textLink);
        } );

        newMenuitem.append( newMenuLink );
        navList.appendChild( newMenuitem );

    } );
        
    console.log( "navList", navList );
    console.log( "navlinks", navlinks );

}
createNavlinks()
