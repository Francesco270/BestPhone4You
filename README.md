# BestPhone4You

## Cos'è?
È una WebApp che aiuta gli utenti nella scelta del loro prossimo Smartphone attraverso una procedura guidata step-by-step.

# Guide e informazioni utili

## Configurare MySQL Workbench per esportare i Database
Prima di poter esportare qualunque Database bisogna impostare MySQL Workbench in modo tale che possa eseguire correttamente l'utility `mysqldump`.

Di default, se si prova ad esportare un Database, si otterrà l'errore `Unknown table 'COLUMN_STATISTICS' in information_schema (1109)`, che è dovuto ad un bug relativo ad un'impostazione non disattivabile che serve a generare istogrammi statistici, non supportata dalla versione di MySQL che stiamo utilizzando.
Infatti la tabella `COLUMN_STATISTICS` non esiste nel DB `information_schema` di MySQL, quindi l'errore si verifica proprio per questo.

Per risolvere questo errore useremo uno script personalizzato che esegue il comando `mysqldump` con il flag `--column-statistics=0`, in modo tale da eseguirlo senza creare gli istogrammi statistici. Seguire questa procedura punto per punto:

1. Aprire un qualunque editor di testo, poi copiare e incollare queste 2 linee:
```
@ECHO OFF
"C:\Program Files\MySQL\MySQL Workbench 8.0 CE\mysqldump.exe" %*  --column-statistics=0
```
**ATTENZIONE:** Al posto di `C:\Program Files\MySQL\MySQL Workbench 8.0 CE\` bisogna scrivere il percorso d'installazione di MySQL Workbench.

2. Salvare il file in una posizione qualsiasi (ad esempio nella cartella di MySQL Workbench)
3. Da MySQL Workbench, andare nella barra superiore e selezionare **Edit > Preferences...**
4. Selezionare **Administration** dal menu laterale sinistro
5. Nella riga dov'è presente la scritta **Path to mysqldump Tool** premere il bottone con all'interno i tre puntini `...`
6. Selezionare il file creato al punto 1
7. Premere il bottone **OK**

In questo modo sarà possibile esportare il DB senza ottenere errori.

## Come esportare il DB usando MySQL Workbench
1. **IMPORTANTE:** Eseguire [questa procedura](#configurare-mysql-workbench-per-esportare-i-database) se non è stata mai fatta
2. Dalla barra superiore selezionare **Server > Data Export**
3. Mettere la spunta al DB da esportare dal menu sinistro
4. Cliccare sul nome del DB per far apparire le tabelle sul menu destro, e selezionare le tabelle da includere nell'esportazione
5. Selezionare **Dump Structure and Data** per esportare i comandi SQL per la creazione delle tabelle e delle rispettive righe
6. Nella sezione **Export Options**, nella riga dov'è presente la scritta **Export to Dump Project Folder**, premere il bottone con all'interno i tre puntini `...` (che si trova a destra della casella di testo)
7. Selezionare la cartella dove si vuole salvare il file di esportazione (nel nostro caso è **database-definitions**)
8. Premere il bottone **Start Export**
9. Nel caso in cui appaia l'avviso **Folder already exists**, premere il bottone **Overwrite**
10. Premere il bottone **Continue Anyway** quando viene visualizzato l'avviso **mysqldump Version Mismatch**

Dopo che il processo di esportazione sarà finito, nella cartella selezionata per l'esportazione saranno presenti i file SQL delle tabelle.
