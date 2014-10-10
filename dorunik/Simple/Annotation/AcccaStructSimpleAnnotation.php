<?php
/**
 * File for class AcccaStructSimpleAnnotation
 * @package Accca
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
/**
 * This class stands for AcccaStructSimpleAnnotation originally named simpleAnnotation
 * Meta informations extracted from the WSDL
 * - from schema : http://www.biosemantics.org:80/ACCCA/AnnotationWebservicePort?xsd=1
 * @package Accca
 * @subpackage Structs
 * @author Mikaël DELSOL <contact@wsdltophp.com>
 * @date 2013-04-09
 */
class AcccaStructSimpleAnnotation extends AcccaWsdlClass
{
	/**
	 * The begin
	 * @var int
	 */
	public $begin;
	/**
	 * The beginLine
	 * @var int
	 */
	public $beginLine;
	/**
	 * The beginWord
	 * @var int
	 */
	public $beginWord;
	/**
	 * The conceptText
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $conceptText;
	/**
	 * The conceptType
	 * Meta informations extracted from the WSDL
	 * - minOccurs : 0
	 * @var string
	 */
	public $conceptType;
	/**
	 * The end
	 * @var int
	 */
	public $end;
	/**
	 * The endLine
	 * @var int
	 */
	public $endLine;
	/**
	 * The endWord
	 * @var int
	 */
	public $endWord;
	/**
	 * Constructor method for simpleAnnotation
	 * @see parent::__construct()
	 * @param int $_begin
	 * @param int $_beginLine
	 * @param int $_beginWord
	 * @param string $_conceptText
	 * @param string $_conceptType
	 * @param int $_end
	 * @param int $_endLine
	 * @param int $_endWord
	 * @return AcccaStructSimpleAnnotation
	 */
	public function __construct($_begin = 0,$_beginLine = 0,$_beginWord = 0,$_conceptText = NULL,$_conceptType = NULL,$_end = 0,$_endLine = 0,$_endWord = 0)
	{
		parent::__construct(array('begin'=>$_begin,'beginLine'=>$_beginLine,'beginWord'=>$_beginWord,'conceptText'=>$_conceptText,'conceptType'=>$_conceptType,'end'=>$_end,'endLine'=>$_endLine,'endWord'=>$_endWord));
	}
	/**
	 * Get begin value
	 * @return int|null
	 */
	public function getBegin()
	{
		return $this->begin;
	}
	/**
	 * Set begin value
	 * @param int the begin
	 * @return int
	 */
	public function setBegin($_begin)
	{
		return ($this->begin = $_begin);
	}
	/**
	 * Get beginLine value
	 * @return int|null
	 */
	public function getBeginLine()
	{
		return $this->beginLine;
	}
	/**
	 * Set beginLine value
	 * @param int the beginLine
	 * @return int
	 */
	public function setBeginLine($_beginLine)
	{
		return ($this->beginLine = $_beginLine);
	}
	/**
	 * Get beginWord value
	 * @return int|null
	 */
	public function getBeginWord()
	{
		return $this->beginWord;
	}
	/**
	 * Set beginWord value
	 * @param int the beginWord
	 * @return int
	 */
	public function setBeginWord($_beginWord)
	{
		return ($this->beginWord = $_beginWord);
	}
	/**
	 * Get conceptText value
	 * @return string|null
	 */
	public function getConceptText()
	{
		return $this->conceptText;
	}
	/**
	 * Set conceptText value
	 * @param string the conceptText
	 * @return string
	 */
	public function setConceptText($_conceptText)
	{
		return ($this->conceptText = $_conceptText);
	}
	/**
	 * Get conceptType value
	 * @return string|null
	 */
	public function getConceptType()
	{
		return $this->conceptType;
	}
	/**
	 * Set conceptType value
	 * @param string the conceptType
	 * @return string
	 */
	public function setConceptType($_conceptType)
	{
		return ($this->conceptType = $_conceptType);
	}
	/**
	 * Get end value
	 * @return int|null
	 */
	public function getEnd()
	{
		return $this->end;
	}
	/**
	 * Set end value
	 * @param int the end
	 * @return int
	 */
	public function setEnd($_end)
	{
		return ($this->end = $_end);
	}
	/**
	 * Get endLine value
	 * @return int|null
	 */
	public function getEndLine()
	{
		return $this->endLine;
	}
	/**
	 * Set endLine value
	 * @param int the endLine
	 * @return int
	 */
	public function setEndLine($_endLine)
	{
		return ($this->endLine = $_endLine);
	}
	/**
	 * Get endWord value
	 * @return int|null
	 */
	public function getEndWord()
	{
		return $this->endWord;
	}
	/**
	 * Set endWord value
	 * @param int the endWord
	 * @return int
	 */
	public function setEndWord($_endWord)
	{
		return ($this->endWord = $_endWord);
	}
	/**
	 * Method returning the class name
	 * @return string __CLASS__
	 */
	public function __toString()
	{
		return __CLASS__;
	}
}
?>