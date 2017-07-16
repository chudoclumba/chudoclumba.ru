<?
/**
 * @author Dick Munroe <munroe@csworks.com>
 * @copyright copyright @ 2006 by Dick Munroe, Cottage Software Works, Inc.
 * @license http://www.csworks.com/publications/ModifiedNetBSD.html
 * @version 1.0.3
 * @package Easy_Email_SMTP
 * @example ./example-backup.php
 * @example ./example-restore.php
 *
 * This class was inspired by the MySQL backup class written by
 * Peyman Hooshmandi Raad.
 *
 * Edit History:
 *
 *  Dick Munroe (munroe@csworks.com) 19-Apr-2006
 *      Make changes as per forum discussions.
 *
 *  Dick Munroe (munroe@csworks.com) 20-Apr-2006
 *      Use database independent classes.
 *
 *  Dick Munroe (munroe@csworks.com) 11-Jun-2006
 *      Minor edits for PHP 5 compatibility.
 *      Add a semi colon to the end of the create table for consistency (and being able
 *      to run the output of this puppy interactively.
 *
 *  Dick Munroe (munroe@csworks.com) 12-Jun-2006
 *      Large queries won't be restored properly.
 */

include_once('DB/class.factory.DB.php') ;

if (!function_exists("cloneObject"))
{
    function &cloneObject(&$o)
    {

        if (version_compare(PHP_VERSION, '5.0', '<'))
        {
            $xxx = $o ;
        }
        else
        {
            $xxx = clone($o) ;
        }

        return $xxx ;
    }
}

/**
 * Backup an SQL database.  Currently this only works with MySQL databases but
 * if additional database specific extensions are written for dm.DB (available
 * from munroe.users.phpclasses.org) then those databases are supported as well.
 *
 * No database specific operations are used in this class so any SQL compliant
 * database should be processable.
 */

class sqlBackup
{
    /**
     * @var object for the type of database to be save or restored.
     * @access private
     */

    var $m_dbObject ;

    /**
     * @var resource the file pointer for the input/output file.
     * @access private
     */

    var $m_fptr;

    /**
     * @var string the name of the output file.
     * @access private
     */

    var $m_output;

    /**
     * @var boolean TRUE if only the structure of the database is to be saved.
     * @access private
     */

    var $m_structureOnly;

    /**
     * @desc Contstuctor
     * @param string [optional] username to use to login to the database
     * @param string [optional] password to use to login to the database.
     * @param string [optional] database to use.
     * @param string [optional] host containing the database.
     * @param string [optional] output file name.
     * @param boolean [optional] TRUE if only the structure is to be saved.
     * @param string [optional] the type of database to be backed up.  Currently
     *                          only MySQL is supported.
     */

    function sqlBackup($user = NULL,
                       $pass = NULL,
                       $db = NULL,
                       $host = NULL,
                       $output = 'sqldata.txt',
                       $structure_only = FALSE,
                       $theType = dmDB_MySQL)
    {
        $this->m_dbObject =& FactoryDB::factory($user, $pass, $db, $host, $theType) ;
        $this->m_output = $output;
        $this->m_structureOnly = $structure_only;
    }

    /**
     * @desc Create the backup of the database.
     * @return void
     * @access private
     */

    function _sqlBackup()
    {
        if ($this->m_output != "")
        {
            $this->m_fptr=fopen($this->m_output,"w");
        }
        else
        {
            $this->m_fptr=false;
        }

        //enumerate tables

        $this->m_dbObject->queryConstant('SHOW TABLES') ;

        while ($theTable =& $this->m_dbObject->fetchRow())
        {
            $theTableName = $theTable[0] ;

            $theDB = cloneObject($this->m_dbObject) ;
            $theCreateTable = $theDB->showCreateTable($theTableName) ;
            $theDB->clear() ;

            $theCreateTable = preg_replace('/\s*\n\s*/', ' ', $theCreateTable) ;
            $theCreateTable = preg_replace('/\(\s*/', '(', $theCreateTable) ;
            $theCreateTable = preg_replace('/\s*\)/', ')', $theCreateTable) ;

            $this->_Out(sprintf("DROP TABLE IF EXISTS `%s` ;\n", $theTableName)) ;

            $this->_Out($theCreateTable . ";\n\n");

            if ($this->m_structureOnly != true)
            {
                $theDB->queryConstant(sprintf('SELECT * FROM %s', $theTableName)) ;

                //parse out the table's data and generate the SQL INSERT statements in order to replicate the data itself...

                $theFieldNames = '' ;

                while ($theDataRow =& $theDB->fetchAssoc())
                {
                    if ($theFieldNames == '')
                    {
                        $theFieldNames = '`' . implode('`, `', array_keys($theDataRow)) . '`' ;
                    }

                    $theData = array() ;

                    foreach ($theDataRow as $theValue)
                    {
                        $theData[] = $theDB->escape_string($theValue) ;
                    }

                    $theData = '"' . implode('", "', $theData) . '"' ;

                    $theInsert = sprintf("INSERT INTO `%s` (%s) VALUES (%s) ;\n",
                                         $theTableName, $theFieldNames,
                                         $theData) ;

                    $this->_Out($theInsert);

                   }

                $this->_Out("\n");
            }

            $theDB->clear() ;
        }

        $this->m_dbObject->clear() ;

        if ($this->m_fptr!=false)
        {
            fclose($this->m_fptr);
        }
    }

    /**
     * @desc Restore a backup file.
     * @returns void
     * @access public
     */

    function Restore()
    {
        $this->m_fptr = fopen($this->m_output, "r") ;

        if ($this->m_fptr === FALSE)
        {
            die(sprintf("Can't open %s", $this->m_output)) ;
        }

        while (!feof($this->m_fptr))
        {
            /*
             * The buffering on fgets may limit the amount of data that can be read.
             * Make sure that a complete SQL statement is available before proceeding
             * with the restore.
             */

            for ($theQuery = fgets($this->m_fptr) ;
                 substr($theQuery, -1, 1) != "\n" ;
                 $theQuery .= fgets($this->m_fptr))
            {} ;
            $theQuery = substr($theQuery, 0, strlen($theQuery) - 1) ;

            if ($theQuery != '')
            {
                $this->m_dbObject->query($theQuery) ;
            }
        }
    }

    /**
     * @desc write an SQL statement to the backup file.
     * @param string The string to be written.
     * @access private
     */

    function _Out($s)
    {
        if ($this->m_fptr === false)
        {
            echo("$s");
        }
        else
        {
            fputs($this->m_fptr,$s);
        }
    }

    /**
     * @desc public interface for backup.
     * @returns void
     * @access public
     */

    function Backup()
    {
        $this->_sqlBackup() ;
    }
}
?>
