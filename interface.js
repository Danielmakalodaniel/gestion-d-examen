const label = document.getElementsByClassName("label")[0];
const input = document.getElementsByClassName("input1")[0];
const input2 = document.getElementsByClassName("input2")[0];
//const input3 = document.getElementsByClassName("input3")[0];
const niveau = document.getElementsByClassName("niveau")[0];
//const nombre = document.getElementsByClassName("nombre")[0];
const annuler = document.getElementById("annuler");
const filiere = document.getElementById("ajouterfiliere");
const salle = document.getElementById("ajoutersalle");
const skill = document.getElementById("skill");
const surveillant = document.getElementById("ajoutersurveillant");
const fermer = document.getElementById("fermer");
const close = document.getElementById("close");
const etudiant = document.getElementsByClassName("ajouteretudiant")[0];
const sv= document.getElementById("suivan");


function borderfocus(){
    label.style.border="2px solid rgb(5, 104, 5)";
}

/*function borderfocus1(){
    nombre.style.border="2px solid rgb(5, 104, 5)";
}*/

function borderfocus2(){
    niveau.style.border="2px solid rgb(5, 104, 5)";
}

function afficherformulairefiliere(){
    document.getElementsByClassName("formulairefiliere")[0].style.display="block";
}

function supprimeformulairefiliere(){
    document.getElementsByClassName("formulairefiliere")[0].style.display="none";
}

function afficherformsalle() {
    document.getElementsByClassName("formulsalle")[0].style.display="block";
}

function afficherformsurveillant() {
    document.getElementById("formulairesurveillant").style.display="block";
}

function supprimerformsalle() {
    document.getElementsByClassName("formulsalle")[0].style.display="none";
}

function supprimerformsurveillant() {
    document.getElementById("formulairesurveillant").style.display="none";
}

function supprimerformetudiant(){
    document.getElementById("formulaireetudiant").style.display="none";
}

function afficherformetudiant(){
    document.getElementById("formulaireetudiant").style.display="block";
}

function examen() {
  if (document.getElementById("suv").value === "" || document.getElementById("ni").value === "" || 
      document.getElementById("fil").value === "" || document.getElementById("sa").value === "" || 
      document.getElementById("nbre").value === "") {
           alert("veuillez entrer tous les champs");
  }else{
    let nbre = document.getElementById("nbre").value;
    if (nbre >0) {
      document.getElementsByClassName("div")[0].style.display="flex";
      document.getElementsByClassName("stricte")[0].style.display="block";
      document.getElementsByClassName("dif")[0].style.display="block";
      sv.style.display="none";

      for (let i = 0; i < nbre; i++) {
        document.getElementsByClassName("stricte")[0].innerHTML += `
                  <div id="formulaire2" style="display: flex;">
                      <input type="text" class="text" name="nom[]" required>
                      <input type="date" class="debut" name="date[]" required>
                      <input type="time" class="startime" name="heuredebut[]" required>
                      <input type="time" class="lastime" name="heurefin[]" required>
                  </div>`;
      }
    }
  }
}



const boutons = document.querySelectorAll('.bnt');

boutons.forEach(btn => {
  btn.addEventListener('click', () => {
    const filiere = btn.dataset.filiere;
    const niveau = btn.dataset.niveau;
    document.getElementsByClassName("sup")[0].style.display="block";
    document.getElementById("messsupprimer").value= filiere;
    document.getElementById("niv").value=niveau;
  });
});

const bouton = document.querySelectorAll('.bnt1');

bouton.forEach(btn => {
  btn.addEventListener('click', () => {
    const salle = btn.dataset.salle;
    document.getElementById("sup").style.display="block";
    document.getElementsByClassName("messsupprime")[0].value= salle;
  });
});

const bouton1 = document.querySelectorAll('.bnt2');

bouton1.forEach(btn => {
  btn.addEventListener('click', () => {
    const nom = btn.dataset.nom;
    const prenom = btn.dataset.prenom;
    document.getElementById("sup1").style.display="block";
    document.getElementsByClassName("messsupprimer")[0].value= nom;
    document.getElementById("niv1").value=prenom;
  });
});

const bouton2 = document.querySelectorAll('.bnt3');

bouton2.forEach(btn => {
  btn.addEventListener('click', () => {
    const nom = btn.dataset.nom;
    const prenom = btn.dataset.prenom;
    document.getElementById("sup2").style.display="block";
    document.getElementsByClassName("mes")[0].value= nom;
    document.getElementById("niv2").value=prenom;
  });
});

input.addEventListener("focus",borderfocus);
input2.addEventListener("focus",borderfocus2);
//input3.addEventListener("focus",borderfocus1);
filiere.addEventListener("click",afficherformulairefiliere);
annuler.addEventListener("click",supprimeformulairefiliere);
salle.addEventListener("click",afficherformsalle);
skill.addEventListener("click",supprimerformsalle);
surveillant.addEventListener("click",afficherformsurveillant);
fermer.addEventListener("click",supprimerformsurveillant);
close.addEventListener("click",supprimerformetudiant);
etudiant.addEventListener("click",afficherformetudiant);
sv.addEventListener("click",examen);