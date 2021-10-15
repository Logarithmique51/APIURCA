
# APIURCA
An API which use Web Scrapping do get informations ( Timetable, Grades ... ) 

It use `PHP` as main language.



**Package used :**
 1. Goutte by fabpot : [Link](https://packagist.org/packages/fabpot/goutte)
 
**Link to post/get request** : https://apiurca.herokuapp.com/

# HOW TO USE

You have the choice between 4 actions : 

 - **credentials** : Return if the login & password belong to the *ebureau*
 - **informations** : Return basic informations (NNE,NAME,...)  (⚠️ Not sure if it work for every accounts )
 - **grades** : Return the grade of each years and more informations (⚠️ Not sure if it work for every accounts )
 - **timetable** : Return the lessons of a given day and group.

## use of each action

### BASIC STRUCTURE

```json
{
    "Login" : "HERE_USERNAME_EBUREAU",
    "Password" : "HERE_PASSWORD_EBUREAU",
    "Action" : "HERE ON OF 4 ACTIONS",
    "Data": {
       "HERE MORE DATA FOR TIMETABLE ACTION"
    }
}
```

### Get Crendentials

- **json to send**

```json
{
    "Login" : "myLogin",
    "Password" : "myPassword",
    "Action" : "credentials"
}
```
- If it's an student who had access to the ebureau it will return 
```json

    "Status" : true

```
- Otherwise it return 
```json

    "Status" : false

```

## Get informations

- **json to send**

```json
{
    "Login" : "myLogin",
    "Password" : "myPassword",
    "Action" : "informations"
}
```
- **Return exemple** 

```json
{   
    "ID": "2170****"
},
{
    "NNE": "19100******"
},
{
    "Name": "SUIVENG BRANDON"
},
{
    "Mail": "brandon.suiveng1@etudiant.univ-reims.fr"
},
{
    "Nationality": "FRANCAIS(E)"
},
{
    "Birthday": "01/01/1998"
},
{
    "BirthCity": "AMIENS"
},
{
    "Department_Country": "SOMME"
}
```
- **Error exemple for wrong username/password**

```json
{
    "Erreur": "Identifiant / Mot de passe incorrect(s)"
}
```

## Get grades

- **json to send**

```json
{
    "Login" : "myLogin",
    "Password" : "myPassword",
    "Action" : "grades"
}
```
- **Return exemple** 

```json
{
    "Years": "2021/2022",
    "Name": "L2 INFO",
    "FormData": {
    },
    "CurrentSession": true
},
{
    "Years": "2020/2021",
    "Name": "L1 INFO",
    "FormData": {
    },
    "SessionName": "Session 1",
    "Note": "12.155/20",
    "Result": "ADM",
    "Mention": "AB",
    "NumberOfSession": 1,
    "CurrentSession": false
},
{
    "Years": "2017/2018",
    "Name": "LICENCE INFORMATIQUE 1ERE ANNEE",
    "FormData": {
    },
    "SessionName": {
        "Session 1": {
            "Note": "DEF",
            "Result": "DEF"
        },
        "Session 2": {
            "Note": "DEF",
            "Result": "DEF"
        }
    },
    "Mention": "",
    "NumberOfSession": 2,
    "CurrentSession": false
}
```

## Get timetable

- **json to send**

```json
{
    "Login" : "myLogin",
    "Password" : "myPassword",
    "Action" : "grades",
    "Data" : {
        "Day":12,
        "Month":10,
        "Year":2021,
        "Group":"auto" OR "yoururl.xml"
    }
}
```

- **Group mode**<br />
  You have 2 choices for the group if you are an user of THOR u can probably use `auto` for the group but if you get this error 
```json
{
  "Erreur": "Impossible de recuperer l'EDT automatiquement veuillez specifier un groupe a la cle 'Group' "
}
```
  You have to use the extension `yoururl.xml` , if you want to know what is it you can access to this json with every groups with their extension <br />
  `Link to json` : https://apiurca.herokuapp.com/groupe_data.json
  
- **Return exemple**
```json
    {
        "Type": "INFO0301",
        "Starttime": "08:00",
        "Endtime": "10:00",
        "Category": "[CM]",
        "Room": "amphi 4 (Moulin de la Housse (SEN, STAPS))"
    },
    {
        "Type": "INFO0303",
        "Starttime": "10:15",
        "Endtime": "12:15",
        "Category": "[TP]",
        "Room": "salle TP 3-S26 (Moulin de la Housse (SEN, STAPS))"
    }
```
## Concrete exemple with jQuery & action "timetable"
```js
var settings = {
  "url": "https://apiurca.herokuapp.com/",
  "method": "POST",
  "timeout": 0,
  "headers": {
    "Content-Type": "application/json"
  },
  "data": JSON.stringify({
    "Login": "urlogin",
    "Password": "urpassword",
    "Action": "timetable",
    "Data": {
      "Day": 12,
      "Month": 10,
      "Year": 2021,
      "Group": "auto"
    }
  }),
};

$.ajax(settings).done(function (response) {
  console.log(response);
});
```

# Thanks
  Any question mail [ME](mailto:suivengbrandon@gmail).
