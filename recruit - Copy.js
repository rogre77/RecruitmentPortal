var ct = 0;
var jid = 1;
var sk = 0;
var skldata = document.getElementById('skillarray').textContent;
//var arrSkill = [];
var arrSkill = skldata.split(",");
    //***************************************************************************************
    //function to add new set of elements (work experience)
    //***************************************************************************************
    function newjob()
    {
      jid++;
      var div1 = document.createElement('div');
      div1.id = jid;
      // link to delete extended form elements
      //var delLnk = '<a href="javascript:delJob('+ jid +')">Del</a>';
      var boot1 = '<div class="form-group col-sm-12"><h4>Job # ';
      var boot2 = '<small><a href="javascript:delJob('+ jid +')"> (Delete)</a></small></h4></div>'
      div1.innerHTML = boot1 + jid + boot2 + document.getElementById('IDWorkTmpl').innerHTML;
      document.getElementById('IDWorkInfo').appendChild(div1);

    }

    //***************************************************************************************
    //function to add new set of elements (work experience)
    //***************************************************************************************
    function newjob1()
    {
      jid = document.getElementById('Jid').value;
      jid++;
      document.getElementById('Jid').value = jid;
      var div1 = document.createElement('div');
      div1.id = jid;
      // link to delete extended form elements
      //var delLnk = '<a href="javascript:delJob('+ jid +')">Del</a>';
      var boot0 = '<div class="UpdtObjWE">'
      var boot1 = '<div class="form-group col-sm-12"><h4>Job # ';
      var boot2 = '<small><a href="javascript:delJob('+ jid +')"> (Delete)</a></small></h4></div>'
      var boot3 = '</div>'
      div1.innerHTML = boot0 + boot1 + jid + boot2 + document.getElementById('IDWorkTmpl').innerHTML + boot3;
      document.getElementById('IDWorkInfo').appendChild(div1);

    }
    
    //***************************************************************************************
    //function to delete the newly added set of elements (work experience)
    //***************************************************************************************
    function delJob(DelId)
    {
      d = document;
      var elem1 = d.getElementById(DelId);
      var parentEle = d.getElementById('IDWorkInfo');
      parentEle.removeChild(elem1);
    }    
    
//***************************************************************************************
//function to add new set of elements (skills)
//***************************************************************************************
function newSCSkill()
{
  var skldata = document.getElementById('skillarray').textContent;
  if (skldata == "") {
    arrSkill = [];
  } else {
    var arrSkill = skldata.split(",");
  }
  var div1 = document.createElement('div');
  div1.id = sk;
  var boot1 = '<div class="col-sm-6 form-group">';
  var boot4 = '</div>';
  // link to delete extended form elements
  var delLink = '<a href="javascript:delIt('+ sk +')">Delete</a>';
  skill = document.getElementById('IdSkillTbl').value;

  if (skill == "Choose...") {
    document.getElementById("error").innerHTML = "Select a skill to add!";
    return false;
  }
  
  if(arrSkill.indexOf(skill)==-1) {
    arrSkill.push(skill);
    var hdn = '<input type="hidden" name="Skill[]" value="' +skill+ '">';
    div1.innerHTML =  boot1 + hdn + skill + boot4 + boot1 + delLink + boot4;
    document.getElementById('SkillsObj').appendChild(div1);
    var hdnskills = document.getElementById('skillarray');
    hdnskills.innerHTML = arrSkill.join();
    document.getElementById("error").innerHTML = "";
    sk++;  
  } else {
    document.getElementById("error").innerHTML = "Skill already exists!";
  }

}

//***************************************************************************************
//function to delete the newly added set of elements (skills)
//***************************************************************************************
function delIt(eleId)
{
  d = document;
  var ele = d.getElementById(eleId);
  var parentEle = d.getElementById('SkillsObj');
  parentEle.removeChild(ele);
  
  var delIndex = arrSkill.indexOf(arrSkill[eleId]);
  delete arrSkill[delIndex];
  var hdnskills = document.getElementById('skillarray');
  hdnskills.innerHTML = arrSkill.join();  
}

//***************************************************************************************
//function to add new set of elements (skills)
//***************************************************************************************
function newskill()
{
  var skldata = document.getElementById('skillarray').textContent;
  if (skldata == "") {
    arrSkill = [];
  } else {
    var arrSkill = skldata.split(",");
  }
  skill = document.getElementById('IdSkillTbl').value;
  ct = document.getElementById('Skillcnt1').innerHTML;
  //alert(ct);
  //alert(arrSkill[0]);
  if (skill == "Choose...") {
    document.getElementById("error").innerHTML = "Select a skill to add!";
    return false;
  }
  
  if(arrSkill.indexOf(skill)==-1) {
    arrSkill.push(skill);
    var div1 = document.createElement('div');
    div1.id = ct;
    var boot1 = '<div class="col-sm-6 form-group">';
    var boot2 = '<div class="col-sm-5 form-group">';
    var boot3 = '<div class="col-sm-1 form-group">';
    var boot4 = '</div>';
    // link to delete extended form elements
    var delLink = '<a href="javascript:delSkill('+ ct +')">Del</a>';
    var rad = '<input type="radio" name="Lvl['+ct+'][0]" value="1" id="IDLvl['+ct+'][0]" checked> Beginner ' + 
              '<input type="radio" name="Lvl['+ct+'][0]" value="2" id="IDLvl['+ct+'][0]" > Intermediate ' +
              '<input type="radio" name="Lvl['+ct+'][0]" value="3" id="IDLvl['+ct+'][0]" > Advance';
    var hdn = '<input type="hidden" name="Skill[' +ct+ ']" value="' +skill+ '">';
    document.getElementById('Skill').innerHTML = skill;      
    div1.innerHTML =  boot1 + hdn + skill + boot4 + boot2 + rad + boot4 + boot3 + delLink + boot4;
    document.getElementById('SkillsObj').appendChild(div1);
    document.getElementById("error").innerHTML = "";
    var hdnskills = document.getElementById('skillarray');
    hdnskills.innerHTML = arrSkill.join();
    ct++;
    document.getElementById('Skillcnt1').innerHTML = ct;
  } else {
    document.getElementById("error").innerHTML = "Skill already exists!";
  }    

}


//***************************************************************************************
//function to delete the newly added set of elements (skills)
//***************************************************************************************
function delSkill(eleId)
{
  var skldata = document.getElementById('skillarray').textContent;
  var arrSkill = skldata.split(",");
  d = document;
  var childEle = d.getElementById(eleId);
  var parentEle = d.getElementById('SkillsObj');
  
  parentEle.removeChild(childEle);
  
  //delete arrSkill[eleId];
  var delIndex = eleId - 100;
  var delIndex = arrSkill.indexOf(arrSkill[delIndex]);
  //arrSkill.splice(delIndex, 1);
  delete arrSkill[delIndex];
  var hdnskills = document.getElementById('skillarray');
  hdnskills.innerHTML = arrSkill.join();

}

//***************************************************************************************
//function to confirm Action
//***************************************************************************************
function ConfirmAction() 
{
  if (confirm('Are you sure you want to do this!?  ')) {
    return true;
  } else {
    return false;
  }
}

//***************************************************************************************
//function to confirm deletion
//***************************************************************************************
function ConfirmDelete(id) 
{
  if (confirm('Are you sure you want to delete this record!?  ' + id)) {
    return true;
  } else {
    return false;
  }
}

//***************************************************************************************
//function to confirm deletion
//***************************************************************************************
function ConfirmMultiDelete(id) 
{
  if (confirm('This will delete related recrods in multiple tables! ' + '\r\n' +  
              'Are you sure you want to delete this record!?  ' + id)) {
    return true;
  } else {
    return false;
  }
}

//***************************************************************************************
//function to confirm rejection of appointment
//***************************************************************************************
function ConfirmAccept(id) 
{
  if (confirm('Are you sure you want to ACCEPT this appointment!?  ' + id)) {
    return true;
  } else {
    return false;
  }
}

//***************************************************************************************
//function to confirm rejection of appointment
//***************************************************************************************
function ConfirmDecline(id) 
{
  if (confirm('Are you sure you want to DECLINE this appointment!?  ' + id)) {
    return true;
  } else {
    return false;
  }
}

//***************************************************************************************
//function to validate appointment form
//***************************************************************************************
function ValidateAppointment()
{
  AptDate = document.getElementById("IdDate").value;
  today = new Date().toISOString().slice(0, 10);
  
  // reject past date
  if (AptDate < today) {
    document.getElementById("error").innerHTML = "Cannot create past appointments!";
    document.getElementById("IdDate").focus();
    return false;   
  }
      
  return true;
}

//***************************************************************************************
//function to validate appointment form
//***************************************************************************************
function ValidateAppointment1()
{
  AptDate = document.getElementById("IdDate").value;
  HidDate = document.getElementById("IdHDate").value;
  
  if (AptDate == HidDate) {
    return true;
  }
  
  today = new Date().toISOString().slice(0, 10);
  
  // reject past date
  if (AptDate < today) {
    document.getElementById("error").innerHTML = "Cannot create past appointments!";
    document.getElementById("IdDate").focus();
    return false;   
  }
      
  return true;
}

//***************************************************************************************
//function to validate applicant registration form
//***************************************************************************************
function ValidateApplicant()
{
  Job1 = document.getElementById("IdJob1").value;
  Duration1 = document.getElementById("IdDuration1").value;
  Title1 = document.getElementById("IdTitle1").value;
  Role1 = document.getElementById("IdRole1").value;
  WERetVal = true;
  ResetTagsColor('IDWorkInfo','label','#428bca');
  
  if (Job1.trim().length > 0) {
    if (Role1.trim().length < 1) {
      document.getElementById('IdRole1').focus();
      document.getElementById('lblRole1').style.backgroundColor = "#d9534f";
      WERetVal = false;
    }      

    if (Title1.trim().length < 1) {
      document.getElementById('IdTitle1').focus();
      document.getElementById('lblTitle1').style.backgroundColor = "#d9534f";
      WERetVal = false;
    }      

    if (Duration1.trim().length < 1  || isNaN(Duration1)) {
      document.getElementById('IdDuration1').focus();
      document.getElementById('lblDuration1').style.backgroundColor = "#d9534f";
      WERetVal = false;
    }          
  }      

  EIRetVal = ValidateEducInfo();
  PIRetVal = ValidatePersonalInfo1();
  
  if(!PIRetVal || !EIRetVal || !WERetVal) {
    document.getElementById('error').innerHTML = "Please correct invalid entries!";
    return false;
  } else {
    return true;
  }
  
}
//***************************************************************************************
//function to validate applicant registration form
//***************************************************************************************
function ValidateEmployer()
{
  CompanyName = document.getElementById("IdCompanyName").value;
  Website = document.getElementById("IdWebsite").value;
  Address = document.getElementById("IdAddress").value;
  Position = document.getElementById("IdPosition").value;
  FName = document.getElementById("IdFirstName").value;
  LName = document.getElementById("IdLastName").value;
  EMail = document.getElementById("IdEmail").value;
  Pwd1 = document.getElementById("IdPassword1").value;
  Pwd2 = document.getElementById("IdPassword2").value;
  Phone = document.getElementById("IdPhone").value;
  retVal = true;
  ResetTagsColor('MyProfile','label','#428bca');

  if (Pwd2.trim().length < 1) {
    document.getElementById('IdPassword2').focus();
    document.getElementById('lblpsw2').style.backgroundColor = "#d9534f";
    retVal = false;
  }     
  
  if (Pwd1.trim().length < 8) {
    document.getElementById('IdPassword1').focus();
    document.getElementById('lblpsw1').style.backgroundColor = "#d9534f";
    retVal = false;
  }      
 
  // compare passwords
  if (Pwd1 != Pwd2) {
    alert("Passwords do not matched!");
    document.getElementById("IdPassword1").focus();
    document.getElementById('lblPsw1').style.backgroundColor = "#d9534f";
    document.getElementById('lblPsw2').style.backgroundColor = "#d9534f";
    retVal = false;
  }
  
  if (Phone.trim().length < 1 || isNaN(Phone)) {
    document.getElementById('IdPhone').focus();
    document.getElementById('lblPhone').style.backgroundColor = "#d9534f";
    retVal = false;
  } 

  if (EMail.trim().length < 1) {
    document.getElementById('IdEmail').focus();
    document.getElementById('lblEmail').style.backgroundColor = "#d9534f";
    retVal = false;
  } 

  if (Position.trim().length < 1) {
    document.getElementById('IdPosition').focus();
    document.getElementById('lblPosition').style.backgroundColor = "#d9534f";
    retVal = false;
  }      

  if (LName.trim().length < 1) {
    document.getElementById('IdLastName').focus();
    document.getElementById('lblLastName').style.backgroundColor = "#d9534f";
    retVal = false;
  }      

  if (FName.trim().length < 1) {
    document.getElementById('IdFirstName').focus();
    document.getElementById('lblFirstName').style.backgroundColor = "#d9534f";
    retVal = false;
  }      

  if (Address.trim().length < 1) {
    document.getElementById('IdAddress').focus();
    document.getElementById('lblAddress').style.backgroundColor = "#d9534f";
    retVal = false;
  }      

  if (Website.trim().length < 1) {
    document.getElementById('IdWebsite').focus();
    document.getElementById('lblWebsite').style.backgroundColor = "#d9534f";
    retVal = false;
  }      

  if (CompanyName.trim().length < 1) {
    document.getElementById('IdCompanyName').focus();
    document.getElementById('lblCompanyName').style.backgroundColor = "#d9534f";
    retVal = false;
  }      

  if (!retVal) {
    document.getElementById('error').innerHTML = "Please correct invalid entries!";
  }
  
  return retVal;
}

//***************************************************************************************
//function to validate applicant registration form
//***************************************************************************************
function ValidatePersonalInfo()
{
  FName = document.getElementById("IdFirstName").value;
  LName = document.getElementById("IdLastName").value;
  Email = document.getElementById("IdEmail").value;
  Pwd1 = document.getElementById("IdPassword1").value;
  Pwd2 = document.getElementById("IdPassword2").value;
  Phone = document.getElementById("IdPhone").value;
  Suburbs = document.getElementById("IdSuburbs").value;
  retVal = true;
  ResetTagsColor('PersonalInfo','label','#428bca');
  PIError = document.getElementById('errorPI');
  if(PIError != null) {
    document.getElementById('errorPI').innerHTML = "";
  }

  if (Pwd2.trim().length < 1) {
    //alert("Please confirm your Password!");
    document.getElementById('IdPassword2').focus();
    document.getElementById('lblPassword2').style.backgroundColor = "#d9534f";
    retVal = false;
  }      
  
  if (Pwd1.trim().length < 8) {
    //alert("Minimum length of password is 8!");
    document.getElementById('IdPassword1').focus();
    document.getElementById('lblPassword1').style.backgroundColor = "#d9534f";
    retVal = false;
  }      

  // compare passwords
  if (Pwd1 != Pwd2) {
    alert("Passwords do not matched!");
    document.getElementById("IdPassword1").focus();
    document.getElementById('lblPassword1').style.backgroundColor = "#d9534f";
    document.getElementById('lblPassword2').style.backgroundColor = "#d9534f";
    retVal = false;
  }

  if (Suburbs == "Choose...") {
    document.getElementById("IdSuburbs").focus();
    document.getElementById('lblSuburbs').style.backgroundColor = "#d9534f";
    retVal = false;
  }
  
  if (Email.trim().length < 1) {
    document.getElementById('IdEmail').focus();
    document.getElementById('lblEmail').style.backgroundColor = "#d9534f";
    retVal = false;
  }      

  if (Phone.trim().length < 1 || isNaN(Phone)) {
    document.getElementById('IdPhone').focus();
    document.getElementById('lblPhone').style.backgroundColor = "#d9534f";
    retVal = false;
  }      

  if (LName.trim().length < 1) {
    document.getElementById('IdLastName').focus();
    document.getElementById('lblLastName').style.backgroundColor = "#d9534f";
    retVal = false;
  }      

  if (FName.trim().length < 1) {
    document.getElementById('IdFirstName').focus();
    document.getElementById('lblFirstName').style.backgroundColor = "#d9534f";
    retVal = false;
  }   

  if (!retVal) {
    if(PIError != null) {
      document.getElementById('errorPI').innerHTML = "Please correct invalid entries!";
    }  }
  
  return retVal;
}

//***************************************************************************************
//function to validate applicant registration form
//***************************************************************************************
function ValidatePersonalInfo1()
{
  FName = document.getElementById("IdFirstName").value;
  LName = document.getElementById("IdLastName").value;
  Email = document.getElementById("IdEmail").value;
  Pwd1 = document.getElementById("IdPassword1").value;
  Pwd2 = document.getElementById("IdPassword2").value;
  Phone = document.getElementById("IdPhone").value;
  Suburbs = document.getElementById("IdSuburbs").value;
  retVal = true;
  ResetTagsColor('PersonalInfo','label','#428bca');
  PIError = document.getElementById('errorPI');
  if(PIError != null) {
    document.getElementById('errorPI').innerHTML = "";
  }

  if (Suburbs == "Choose...") {
    document.getElementById("IdSuburbs").focus();
    document.getElementById('lblSuburbs').style.backgroundColor = "#d9534f";
    retVal = false;
  }
  
  if (Phone.trim().length < 1 || isNaN(Phone)) {
    document.getElementById('IdPhone').focus();
    document.getElementById('lblPhone').style.backgroundColor = "#d9534f";
    retVal = false;
  }      

  if (Pwd2.trim().length < 1) {
    //alert("Please confirm your Password!");
    document.getElementById('IdPassword2').focus();
    document.getElementById('lblPassword2').style.backgroundColor = "#d9534f";
    retVal = false;
  }      
  
  if (Pwd1.trim().length < 8) {
    //alert("Minimum length of password is 8!");
    document.getElementById('IdPassword1').focus();
    document.getElementById('lblPassword1').style.backgroundColor = "#d9534f";
    retVal = false;
  }      

  // compare passwords
  if (Pwd1 != Pwd2) {
    alert("Passwords do not matched!");
    document.getElementById("IdPassword1").focus();
    document.getElementById('lblPassword1').style.backgroundColor = "#d9534f";
    document.getElementById('lblPassword2').style.backgroundColor = "#d9534f";
    retVal = false;
  }

  if (Email.trim().length < 1) {
    document.getElementById('IdEmail').focus();
    document.getElementById('lblEmail').style.backgroundColor = "#d9534f";
    retVal = false;
  }      

  if (LName.trim().length < 1) {
    document.getElementById('IdLastName').focus();
    document.getElementById('lblLastName').style.backgroundColor = "#d9534f";
    retVal = false;
  }      

  if (FName.trim().length < 1) {
    document.getElementById('IdFirstName').focus();
    document.getElementById('lblFirstName').style.backgroundColor = "#d9534f";
    retVal = false;
  }   

  if (!retVal) {
    if(PIError != null) {
      document.getElementById('errorPI').innerHTML = "Please correct invalid entries!";
    }  }
  
  return retVal;
}

//***************************************************************************************
//function to validate applicant registration form
//***************************************************************************************
function ValidateEducInfo()
{
  Degree = document.getElementById("IdDegreeDesc").value;
  DegreeYear = document.getElementById("IdDegreeGYear").value;
  PostGraduate = document.getElementById("IdPGradDesc").value;
  PostGraduateYear = document.getElementById("IdPGradGYear").value;
  Masters = document.getElementById("IdMastersDesc").value;
  MastersYear = document.getElementById("IdMastersGYear").value;
  Doctorate = document.getElementById("IdDoctorateDesc").value;
  DoctorateYear = document.getElementById("IdDoctorateGYear").value;
  retVal = true;
  ResetTagsColor('EducationalInfo','label','#428bca');
  EIError = document.getElementById('errorEI');
  if(EIError != null) {
    document.getElementById('errorEI').innerHTML = "";
  }
  
  if (Doctorate.trim().length > 0) {
    if (DoctorateYear.trim().length < 1 || isNaN(DoctorateYear)) {
      document.getElementById('IdDoctorateGYear').focus();
      document.getElementById('lblDoctorateGYear').style.backgroundColor = "#d9534f";
      retVal = false;
    }          
  }

  if (DoctorateYear.trim().length > 0) {
    if (Doctorate.trim().length < 1) {
      document.getElementById('IdDoctorateDesc').focus();
      document.getElementById('lblDoctorate').style.backgroundColor = "#d9534f";
      retVal = false;
    }          
  }

  if (Masters.trim().length > 0) {
    if (MastersYear.trim().length < 1 || isNaN(MastersYear)) {
      document.getElementById('IdMastersGYear').focus();
      document.getElementById('lblMastersGYear').style.backgroundColor = "#d9534f";
      retVal = false;
    }          
  }

  if (MastersYear.trim().length > 0) {
    if (Masters.trim().length < 1) {
      document.getElementById('IdMastersDesc').focus();
      document.getElementById('lblMasters').style.backgroundColor = "#d9534f";
      retVal = false;
    }          
  }

  if (PostGraduate.trim().length > 0) {
    if (PostGraduateYear.trim().length < 1 || isNaN(PostGraduateYear)) {
      document.getElementById('IdPGradGYear').focus();
      document.getElementById('lblPostGraduateGYear').style.backgroundColor = "#d9534f";
      retVal = false;
    }          
  }

  if (PostGraduateYear.trim().length > 0) {
    if (PostGraduate.trim().length < 1) {
      document.getElementById('IdPGradDesc').focus();
      document.getElementById('lblPostGraduate').style.backgroundColor = "#d9534f";
      retVal = false;
    }          
  }

  if (Degree.trim().length > 0) {
    if (DegreeYear.trim().length < 1 || isNaN(DegreeYear)) {
      document.getElementById('IdDegreeGYear').focus();
      document.getElementById('lblDegreeGYear').style.backgroundColor = "#d9534f";
      retVal = false;
    }          
  }

  if (DegreeYear.trim().length > 0) {
    if (Degree.trim().length < 1) {
      document.getElementById('IdDegreeDesc').focus();
      document.getElementById('lblDegree').style.backgroundColor = "#d9534f";
      retVal = false;
    }          
  }

  if (!retVal) {
    if(EIError != null) {
      document.getElementById('errorEI').innerHTML = "Please correct invalid entries!";
    }
  }
  
  return retVal;
}

//***************************************************************************************
//function to go back to previous page
//***************************************************************************************
function goBack() {
    window.history.back();
}

//***************************************************************************************
//function to toggle hide/show of classes
//***************************************************************************************
function ShowHideClass(a,b) {
  var myClasses = document.querySelectorAll(a),
    i = 0,
    l = myClasses.length;

  for (i; i < l; i++) {
    if(myClasses[i].style.display=="none"){
      myClasses[i].style.display = 'inline';
    } else {
      myClasses[i].style.display = 'none';
    }
  }
  HideShowClass(b);
  ResetTagsColor('*','label','#428bca');
}    
    
function HideShowClass(b) {
  var myClasses = document.querySelectorAll(b),
    i = 0,
    l = myClasses.length;

  for (i; i < l; i++) {
    if(myClasses[i].style.display=="none"){
      myClasses[i].style.display = 'inline';
    } else {
      myClasses[i].style.display = 'none';
    }
  }
}     
    
//***************************************************************************************
//function to validate passwords
//***************************************************************************************
function ValidatePasswords()
{
  pwd1 = document.getElementById("IdPassword1").value;
  pwd2 = document.getElementById("IdPassword2").value;
      
  if (pwd1.length < 8) {
    alert("Password Length should not be less than 8!");
    document.getElementById("IdPassword1").focus();
    return false; 
  }
      
  if (pwd2.length < 8) {
    alert("Password Length should not be less than 8!");
    document.getElementById("IdPassword2").focus();
    return false; 
  }
      
  // compare passwords
  if (pwd1 != pwd2) {
    alert("Passwords do not matched!");
    document.getElementById("IdPassword1").focus();
    return false;   
  }
      
  return true;
}   

//***************************************************************************************
//function to validate passwords
//***************************************************************************************
function ResetTagsColor(parentId,tagName,color)
{
  prntnode = document.getElementById(parentId);

  if (parentId=="*") {
    allTags = document.getElementsByTagName("label");
    PIError = document.getElementById('errorPI');
    EIError = document.getElementById('errorEI');
    if(PIError != null) {
      document.getElementById('errorPI').innerHTML = "";
    }
    if(EIError != null) {
      document.getElementById('errorEI').innerHTML = "";
    }
  } else {
    allTags = prntnode.getElementsByTagName("label");
  }
  
  for (var i = 0; i < allTags.length; i++) {
    tagId = allTags[i].id;
    //alert(tagId);
    if (tagId == "") {
      continue;
    }
    document.getElementById(tagId).style.backgroundColor = color;
  }    
  return true;
}

//***************************************************************************************
//function to validate passwords
//***************************************************************************************
function ResetTags1(parentId,tagParm)
{
  alert("reset");
  prntnode = document.getElementById(parentId);
    
      alert(prntnode.childNodes.length);
  for (var ii = 0; ii < prntnode.childNodes.length; ii++)
  {
      var childId = prntnode.childNodes[ii].id;
      alert("Child " + childId);
     if(childId.tagName == tagParm)
      alert(childId.tagName);
  }
//document.getElementById("error").innerHTML = arr;  
  return false;
}

//***************************************************************************************
//function to validate applicant registration form
//***************************************************************************************
function ValidateApplicant1()
{
  FName = document.getElementById("IdFName").value;
  LName = document.getElementById("IdLName").value;
  EMail = document.getElementById("IdEmail").value;
  Pwd1 = document.getElementById("IdPassword1").value;
  Pwd2 = document.getElementById("IdPassword2").value;
  Phone = document.getElementById("IdPhone").value;
  Suburbs = document.getElementById("IdSuburbs").value;
  Degree = document.getElementById("IdDegreeDesc").value;
  DegreeYear = document.getElementById("IdDegreeGYear").value;
  PostGraduate = document.getElementById("IdPGradDesc").value;
  PostGraduateYear = document.getElementById("IdPGradGYear").value;
  Masters = document.getElementById("IdMastersDesc").value;
  MastersYear = document.getElementById("IdMastersGYear").value;
  Doctorate = document.getElementById("IdDoctorateDesc").value;
  DoctorateYear = document.getElementById("IdDoctorateGYear").value;

  Job1 = document.getElementById("IdJob1").value;
  Duration1 = document.getElementById("IdDuration1").value;
  Title1 = document.getElementById("IdTitle1").value;
  Role1 = document.getElementById("IdRole1").value;

  // validate lengths
  if (FName.length < 1) {
      alert("Please fill your First Name!");
      document.getElementById('IdFName').focus();
      return false;    
  }      

  if (LName.length < 1) {
      alert("Please fill your Last Name!");
      document.getElementById('IdLName').focus();
      return false;    
  }      

  if (EMail.length < 1) {
      alert("Please fill your Email Address!");
      document.getElementById('IdEmail').focus();
      return false;    
  } 

  if (Pwd1.length < 8) {
      alert("Minimum length of password is 8!");
      document.getElementById('IdPassword1').focus();
      return false;    
  }      

  if (Pwd2.length < 1) {
      alert("Please confirm your Password!");
      document.getElementById('IdPassword2').focus();
      return false;    
  }      

  // compare passwords
  if (Pwd1 != Pwd2) {
    alert("Passwords do not matched!");
    document.getElementById("IdPassword1").focus();
    return false;   
  }

  if (Phone.length < 1) {
      alert("Please fill your Phone Number!");
      document.getElementById('IdPhone').focus();
      return false;    
  } 

  if (isNaN(Phone)) {
    alert("Only numbers are allowed in Phone!");
    document.getElementById("IdPhone").focus();
    return false;   
  }

  if (Suburbs == "Choose...") {
    alert("Kindly select a City or Suburb!");
    document.getElementById("IdSuburbs").focus();
    return false;   
  }

  if (Degree.length > 0) {
    if (DegreeYear.length < 1) {
        alert("Please fill your Year Graduated!");
        document.getElementById('IdDegreeGYear').focus();
        return false;    
    }          
    if (isNaN(DegreeYear)) {
      alert("Please input a valid Year!");
      document.getElementById("IdDegreeGYear").focus();
      return false;   
    }
  }

  if (DegreeYear.length > 0) {
    if (Degree.length < 1) {
        alert("Please fill your Degree Description!");
        document.getElementById('IdDegreeDesc').focus();
        return false;    
    }          
  }

  if (PostGraduate.length > 0) {
    if (PostGraduateYear.length < 1) {
        alert("Please fill your Year Graduated!");
        document.getElementById('IdPGradGYear').focus();
        return false;    
    }          
    if (isNaN(PostGraduateYear)) {
      alert("Please input a valid Year!");
      document.getElementById("IdPGradGYear").focus();
      return false;   
    }
  }

  if (PostGraduateYear.length > 0) {
    if (PostGraduate.length < 1) {
        alert("Please fill your Post Graduate Description!");
        document.getElementById('IdPGradDesc').focus();
        return false;    
    }          
  }

  if (Masters.length > 0) {
    if (MastersYear.length < 1) {
        alert("Please fill your Year Graduated!");
        document.getElementById('IdMastersGYear').focus();
        return false;    
    }          
    if (isNaN(MastersYear)) {
      alert("Please input a valid Year!");
      document.getElementById("IdMastersGYear").focus();
      return false;   
    }
  }

  if (MastersYear.length > 0) {
    if (Masters.length < 1) {
        alert("Please fill your Masters Description!");
        document.getElementById('IdMastersDesc').focus();
        return false;    
    }          
  }

  if (Doctorate.length > 0) {
    if (DoctorateYear.length < 1) {
        alert("Please fill your Year Graduated!");
        document.getElementById('IdDoctorateGYear').focus();
        return false;    
    }          
    if (isNaN(DoctorateYear)) {
      alert("Please input a valid Year!");
      document.getElementById("IdDoctorateGYear").focus();
      return false;   
    }
  }

  if (DoctorateYear.length > 0) {
    if (Doctorate.length < 1) {
        alert("Please fill your Doctorate Description!");
        document.getElementById('IdDoctorateDesc').focus();
        return false;    
    }          
  }

  // validate work experience
  if (Job1.length > 0) {
    if (Duration1.length < 1) {
        alert("Please fill your Work Duration!");
        document.getElementById('IdDuration1').focus();
        return false;    
    }          
    if (isNaN(Duration1)) {
      alert("Please input a valid Work Duration!");
      document.getElementById("IdDoctorateGYear").focus();
      return false;   
    }
    if (Title1.length < 1) {
        alert("Please fill your Job Title!");
        document.getElementById('IdTitle1').focus();
        return false;    
    }          
    if (Role1.length < 1) {
        alert("Please fill your Job Role!");
        document.getElementById('IdRole1').focus();
        return false;    
    }          
  }      

  return true;
}

//***************************************************************************************
//Function to check existence of email in database 
//***************************************************************************************
function CheckMail(email) {
  if (email.trim().length < 1) {
    document.getElementById("emailHelp").innerHTML = "Please provide a valid email address.";
    document.getElementById('IdEmail').focus();
    return;
  } 
  
  var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  //var filter = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/
  if (!filter.test(email)) {
    document.getElementById("emailHelp").innerHTML = "Please provide a valid email address.";
    document.getElementById('IdEmail').focus();
    return;  
  } 
  
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp = new XMLHttpRequest();
  } else {
    // code for IE6, IE5
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      resp = this.responseText;
      if (resp != 0) {
        document.getElementById("emailHelp").innerHTML = "Email account already registered!";
        document.getElementById('IdEmail').focus();
      } else {
        document.getElementById("emailHelp").innerHTML = "";
      }
      return;
    }
  }
  xmlhttp.open("GET","CheckMail.php?m="+email);
  xmlhttp.send();
}

