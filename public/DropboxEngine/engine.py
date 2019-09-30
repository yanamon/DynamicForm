import pymysql
import time
import json
import dropbox
import urllib.request
from dropbox.files import WriteMode

def uploadFileDropbox(local_path, dropbox_path, token):
    dropbx = dropbox.Dropbox(token)
    print("Upload " + dropbox_path)
    with open(local_path, "rb") as f:
        dropbx.files_upload(f.read(), '/' + dropbox_path, mode=dropbox.files.WriteMode.overwrite)

def downloadFileDropbox (file,token):
    dropbx = dropbox.Dropbox(token)
    dropbx.files_download_to_file("json-download/" + file, '/' + file)
    print("Download " + file)

def countData(cursor, table):
    cursor.execute("SELECT COUNT(id) FROM %s" %table)
    data = cursor.fetchone()
    jum_data = data[0]
    return jum_data

def accessJsonFile(namaJsonFile, action):
    with open(namaJsonFile, action, encoding='utf-8')as outfile:
        try:
            dataJson = json.load(outfile)
        except:
            dataJson = []
    return dataJson

def appendToJson(row, tableColumn, table, namaJsonFile, action):
    newDataJson = {}
    for i, data in enumerate(row):
        column = tableColumn[i][0]
        newDataJson[column] = str(data);
    newDataJson['table'] = table
    newDataJson['action'] = action
    newDataJson['tersinkronisasi'] = False
    dataJson = accessJsonFile(namaJsonFile, "r")
    dataJson.append(newDataJson)
    with open(namaJsonFile, "r+", encoding='utf-8')as outfile:
        json.dump(dataJson, outfile, indent=4)

def updateTersinkronisasi(namaJsonFile):
    fileJson = open(namaJsonFile, "r+")
    dataJson = json.load(fileJson)
    for row in dataJson:
        row['tersinkronisasi'] = True
        fileJson.seek(0)
        json.dump(dataJson, fileJson, indent=4)
        fileJson.truncate()



# con = pymysql.connect(host="localhost", user="root", database="dynamic_form")
# cur = con.cursor()
while True :
    delay = 5
    time.sleep(delay)
    print("Listening")


