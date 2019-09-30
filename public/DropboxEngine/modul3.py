import pymysql
import time
import json
import dropbox
from dropbox.files import WriteMode

def uploadFileDropbx(file):
    dropbx = dropbox.Dropbox("apa_LdNqwrsAAAAAAAABZUMyNsV_5ZkVV5urLY6nxVn8wJpt0YU_MYQRb0WfRa3l")
    print("Upload " + file)
    with open(file, "rb") as f:
        dropbx.files_upload(f.read(), '/' + file, mode=dropbox.files.WriteMode.overwrite)

def downloadFileDropbox (file):
    dropbx = dropbox.Dropbox("apa_LdNqwrsAAAAAAAABZUMyNsV_5ZkVV5urLY6nxVn8wJpt0YU_MYQRb0WfRa3l")
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

def insert(con1, con2, cursor1, cursor2, db1,  db2, table):
    cursor1.execute("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '%s' AND TABLE_NAME = '%s'" % (db1, table))
    cursor2.execute("SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '%s' AND TABLE_NAME = '%s'" % (db2, table))
    increment1 = cursor1.fetchone()
    increment2 = cursor2.fetchone()
    if (increment1[0] > increment2[0]):
        selisih_data = increment1[0] - increment2[0]
        cursor1.execute("SELECT * FROM %s ORDER BY id DESC LIMIT %s" % (table, selisih_data))
        data_copy = cursor1.fetchall()
        cursor1.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'db_toko' AND TABLE_NAME = '%s'" % table)
        table_column = cursor1.fetchall()
        for row in data_copy:
            appendToJson(row, table_column, table, "toko.json", "insert")
            uploadFileDropbx("toko.json")
        downloadFileDropbox("toko.json")
        dataJson = accessJsonFile("json-download/toko.json", "r")
        for row in dataJson:
            if row['action'] == 'insert' and row['tersinkronisasi'] == False:
                sql = "INSERT INTO %s VALUES(" % (table)
                sync = ""
                for i, row2 in enumerate(row):
                    if (i != len(row)-1 and i != len(row)-2 and i != len(row)-3):
                        sql = sql + "'" + row[row2] + "'"
                        sync = sync + "'" + row[row2] + "'"
                        if (i != len(row)-4):
                            sql = sql + ", "
                            sync = sync + ", "
                sql = sql + ")"
                cursor2.execute(sql)
                con2.commit()
                updateTersinkronisasi("toko.json")
                print("Data Tersinkronisasi ke db_bank : INSERT INTO %s VALUES(%s)" %(table, sync))

    if (increment2[0] > increment1[0]):
        selisih_data = increment2[0] - increment1[0]
        cursor2.execute("SELECT * FROM %s ORDER BY id DESC LIMIT %s" % (table, selisih_data))
        data_copy = cursor2.fetchall()
        cursor2.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'db_bank' AND TABLE_NAME = '%s'" % table)
        table_column = cursor2.fetchall()
        for row in data_copy:
            appendToJson(row, table_column, table, "bank.json", "insert")
            uploadFileDropbx("bank.json")
        downloadFileDropbox("toko.json")
        dataJson = accessJsonFile("json-download/bank.json", "r")
        for row in dataJson:
            if row['action'] == 'insert' and row['tersinkronisasi'] == False:
                sql = "INSERT INTO %s VALUES(" % (table)
                sync = ""
                for i, row2 in enumerate(row):
                    if (i != len(row) - 1 and i != len(row) - 2 and i != len(row)-3):
                        sql = sql + "'" + row[row2] + "'"
                        sync = sync + "'" + row[row2] + "'"
                        if (i != len(row) - 4):
                            sql = sql + ", "
                            sync = sync + ", "
                sql = sql + ")"
                cursor1.execute(sql)
                con1.commit()
                updateTersinkronisasi("bank.json")
                print("Data Tersinkronisasi ke db_toko : INSERT INTO %s VALUES(%s)" % (table, sync))


def delete(con1, con2, cursor1, cursor2, table):
    cursor1.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'db_toko' AND TABLE_NAME = '%s'" % table)
    table_column = cursor1.fetchall()
    jum_data_toko2 = countData(cursor1, table)
    jum_data_bank2 = countData(cursor2, table)
    if (jum_data_bank2 > jum_data_toko2):
        cursor1.execute("SELECT * FROM %s" % table)
        cursor2.execute("SELECT * FROM %s" % table)
        data_transaksi_toko = cursor1.fetchall()
        data_transaksi_bank = cursor2.fetchall()
        for row1 in data_transaksi_bank:
            delete = True
            for row2 in data_transaksi_toko:
                if (row1[0] == row2[0]):
                    delete = False
                    break
            if (delete == True):
                appendToJson(row1, table_column, table, "toko.json", "delete")
                uploadFileDropbx("toko.json")
        downloadFileDropbox("toko.json")
        dataJson = accessJsonFile("json-download/toko.json", "r")
        for row in dataJson:
            if row['action'] == 'delete' and row['tersinkronisasi'] == False:
                cursor2.execute("DELETE FROM %s WHERE id = %s" % (table, row["id"]))
                con2.commit()
                updateTersinkronisasi("toko.json")
                print("Data Tersinkronisasi ke db_bank : DELETE FROM %s WHERE id = %s" % (table, row["id"]))

    if (jum_data_toko2 > jum_data_bank2):
        cursor2.execute("SELECT * FROM %s" % table)
        cursor1.execute("SELECT * FROM %s" % table)
        data_transaksi_bank = cursor2.fetchall()
        data_transaksi_toko = cursor1.fetchall()
        for row1 in data_transaksi_toko:
            delete = True
            for row2 in data_transaksi_bank:
                if (row1[0] == row2[0]):
                    delete = False
                    break
            if (delete == True):
                appendToJson(row1, table_column, table, "bank.json", "delete")
                uploadFileDropbx("bank.json")
        downloadFileDropbox("toko.json")
        dataJson = accessJsonFile("json-download/bank.json", "r")
        for row in dataJson:
            if row['action'] == 'delete' and row['tersinkronisasi'] == False:
                cursor1.execute("DELETE FROM %s WHERE id = %s" % (table, row["id"]))
                con1.commit()
                updateTersinkronisasi("bank.json")
                print("Data Tersinkronisasi ke db_toko : DELETE FROM %s WHERE id = %s" % (table, row["id"]))

def update(con1, con2, cursor1, cursor2, table):
    cursor2.execute("SELECT * FROM %s" % table)
    cursor1.execute("SELECT * FROM %s" % table)
    data_transaksi_bank = cursor2.fetchall()
    data_transaksi_toko = cursor1.fetchall()
    cursor1.execute("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'db_bank' AND TABLE_NAME = '%s'" % table)
    table_column = cursor1.fetchall()
    for row1 in data_transaksi_toko:
        for row2 in data_transaksi_bank:
            updateToToko = False
            updateToBank = False
            if (row1[0] == row2[0]):
                cursor2.execute("SELECT updated_at FROM %s WHERE %s = %s" % (table, table_column[0][0], row2[0]))
                cursor1.execute("SELECT updated_at FROM %s WHERE %s = %s" % (table, table_column[0][0], row1[0]))
                updated_at_bank = cursor2.fetchone()
                updated_at_toko = cursor1.fetchone()
                if (updated_at_toko[0] < updated_at_bank[0]):
                    i = 0
                    for column in table_column:
                        if (row1[i] != row2[i] and column[0]!="updated_at"):
                            updateToToko = True
                        i = i +1
                    if(updateToToko == True):
                        appendToJson(row2, table_column, table, "bank.json", "update")
                        uploadFileDropbx("bank.json")
                    downloadFileDropbox("toko.json")
                    dataJson = accessJsonFile("json-download/bank.json", "r")
                    for rowJson1 in dataJson:
                        if rowJson1['action'] == 'update' and rowJson1['tersinkronisasi'] == False:
                            for i, rowJson2 in enumerate(rowJson1):
                                if (rowJson2 != 'action' and rowJson2 != 'tersinkronisasi' and rowJson2 != 'table'):
                                    cursor1.execute("UPDATE %s SET %s = '%s' WHERE id = %s" % (table, rowJson2, rowJson1[rowJson2], rowJson1["id"]))
                                    con1.commit()
                                    updateTersinkronisasi("bank.json")
                            print("Data Tersinkronisasi ke db_toko : UPDATE %s WHERE id = %s" % (table, rowJson1["id"]))

                elif (updated_at_toko[0] > updated_at_bank[0]):
                    i = 0
                    for column in table_column:
                        if (row1[i] != row2[i] and column[0]!="updated_at"):
                            updateToBank = True
                        i = i + 1
                    if (updateToBank == True):
                        appendToJson(row1, table_column, table, "toko.json", "update")
                        uploadFileDropbx("toko.json")
                    downloadFileDropbox("toko.json")
                    dataJson = accessJsonFile("json-download/toko.json", "r")

                    for i, rowJson1 in enumerate(dataJson):
                        if rowJson1['action'] == 'update' and rowJson1['tersinkronisasi'] == False:
                            for i, rowJson2 in enumerate(rowJson1):
                                if (rowJson2 != 'action' and rowJson2 != 'tersinkronisasi' and rowJson2 != 'table'):
                                    cursor2.execute("UPDATE %s SET %s = '%s' WHERE id = %s" % (table, rowJson2, rowJson1[rowJson2], rowJson1["id"]))
                                    con2.commit()
                                    updateTersinkronisasi("toko.json")
                            print("Data Tersinkronisasi ke db_bank : UPDATE %s WHERE id = %s"% (table,  rowJson1["id"]))

con_toko = pymysql.connect(host="localhost", user="root", database="db_toko")
con_bank = pymysql.connect(host="localhost", user="root", database="db_bank")
cur_toko = con_toko.cursor()
cur_bank = con_bank.cursor()
cur_toko.execute("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'db_toko'")
tabel_db = cur_toko.fetchall()
data = accessJsonFile("bank.json", "w");
data = accessJsonFile("toko.json", "w");

while True :
    delay = 10
    time.sleep(delay)
    print("Listening")

    for table in tabel_db:
        insert(con_toko, con_bank, cur_toko, cur_bank, "db_toko", "db_bank", table[0])
        delete(con_toko, con_bank, cur_toko, cur_bank, table[0])
        update(con_toko, con_bank, cur_toko, cur_bank, table[0])
    con_toko.rollback()
    con_bank.rollback()