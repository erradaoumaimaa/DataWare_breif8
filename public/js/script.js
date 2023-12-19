function openPopup(userID) {
    document.getElementById('userID').value = userID;
    document.getElementById('popup').style.display = 'flex';
}

function closePopup() {
    document.getElementById('popup').style.display = 'none';
}

function openMyPopup() {
    document.getElementById('myPopup').style.display = 'flex';
}

function closeMyPopup() {
    document.getElementById('myPopup').style.display = 'none';
}

function openTeamPopup(userID) {
    document.getElementById('scrumMaster').value = userID;
    document.getElementById('teamPopup').style.display = 'flex';
}

function closeTeamPopup() {
    document.getElementById('teamPopup').style.display = 'none';
}

function openSMPopup(teamId) {
    document.getElementById('teamId').value = teamId;

    document.getElementById('SMpopup').style.display = 'flex';
}

function closeSMPopup() {
    document.getElementById('SMpopup').style.display = 'none';
}
