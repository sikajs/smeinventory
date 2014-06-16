<?php

require_once dirname(__FILE__) . '/../../unused/class.wordcount.php';

/**
 * Description of class
 *
 * @author sika
 */
class TestWordCount extends PHPUnit_Framework_TestCase {

    public function testCountWords() {
        $Wc = new WordCount();
        $TestSentence = "my name is afif";
        $WordCount = $Wc->countWords($TestSentence);
        $this->assertEquals(4, $WordCount);
    }

    public function testCountWordsWithSpaces() {
        $Wc = new WordCount();
        $testSentence = "my name is Anonymous ";
        $wordCount = $Wc->countWords($testSentence);
        $this->assertEquals(4, $wordCount);
    }

    public function testCountWordsWithNewLine() {
        $Wc = new WordCount();
        $TestSentence = "my name is \n\r Anonymous";
        $WordCount = $Wc->countWords($TestSentence);
        $this->assertEquals(4, $WordCount);
    }

}

?>
