<?php
//server.gs:

function doGet(e) {
  return HtmlService.createHtmlOutputFromFile('form.html');
}

function uploadFiles(form) {
  
  try {
    var uploader_name = form.myName;
    if(uploader_name.length < 2)  return "Your Name must have at least 2 characters</br>Tên bạn phải có ít nhất 2 ký tự";
    
    var blob = form.myFile;
    if(typeof blob == 'undefined' || blob == null || !blob) return "None file selected</br>Không có file nào được chọn";
    
    //var dropbox = "Anigoo_Upload_Share_Folder_(None_Share)";
    //var upload_share_folder = createNewSubFolderinRoot(dropbox);
    var upload_share_folder = DriveApp.getFolderById("1JvbGByTlOrlFfxeQdEE6UpEEqmftaNSW");
    
    var file = upload_share_folder.createFile(blob);   
    file.setSharing(DriveApp.Access.ANYONE, DriveApp.Permission.VIEW);
    file.setDescription("Uploaded by " + uploader_name);
    
    
    var fileUrl = "https://drive.google.com/open?id=" + file.getId();
    
    var returnString = "File uploaded successfully";
    
    if(isImage(file)) returnString += "<img src=\"https://drive.google.com/a/student.hust.edu.vn/uc?export=view&id=" + file.getId() + "\">";
    returnString += "</br>File Name: " + file.getName();
    returnString += "<input type=\"text\" autofocus value=\"" + file.getId() + "\">"
    returnString += "</br>Link: <a target=\"_blank\" href=\"" + fileUrl + "\">" + fileUrl + "</a>";
    returnString += "</br>Date Created: " + formatDate(file.getDateCreated());
    returnString += "</br>Size: " + file.getSize() + " Bytes";
    
    return returnString;
    
  } catch (error) {
    
    return error.toString();
  }
  
}


//Trả về folder id con có tên là name của folder, nếu không tồn tại thì tạo 1 folder con mới tên là name
function createNewSubFolder(SubFolderName, Folder){
    var sub_folder,sub_folders = Folder.getFoldersByName(SubFolderName);
    if (sub_folders && sub_folders.hasNext()) {
      sub_folder = sub_folders.next();
    } else {
      sub_folder = Folder.createFolder(SubFolderName);
    }
  return sub_folder;
}


function isImage(file){
var mime_type = file.getMimeType();
  return (mime_type.indexOf("image/")>-1);
}

//Trả về folder id con có tên là name của root folder, nếu không tồn tại thì tạo 1 folder con mới tên là name
function createNewSubFolderinRoot(SubFolderName){
    var sub_folder,sub_folders = DriveApp.getFoldersByName(SubFolderName);
    if (sub_folders && sub_folders.hasNext()) {
      sub_folder = sub_folders.next();
    } else {
      sub_folder = DriveApp.createFolder(SubFolderName);
    }
  return sub_folder;
}

function formatDate(date){
return Utilities.formatDate(date, "GMT+7", "yyyy-MM-dd HH:mm:ss");
}
?>

