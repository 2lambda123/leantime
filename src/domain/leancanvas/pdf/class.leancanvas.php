<?php
/**
 * HTML code for PDF report
 */
namespace leantime\domain\pdf {
  
	use leantime\domain\repositories;
	
    class leancanvas extends \leantime\domain\pdf\canvas {

		protected const CANVAS_NAME = 'lean';

        /**
         * htmlCanvas -  Layout canvas (must be implemented)
         *
         * @access public
         * @param  array  $recordsAry Array of canvas data records
         * @return string HTML code
         */
        protected function htmlCanvas(array $recordsAry): string
        {
			
			$html = '<table class="canvas" style="width: 100%"><tbody>'.
				'<tr>'.
				'  <td class="canvas-elt-title" style="width: 10%;" colspan="2">'.
                $this->htmlCanvasTitle($this->canvasTypes['problem']['title'], $this->canvasTypes['problem']['icon']).'</td>'.
				'  <td class="canvas-elt-title" style="width: 10%;" colspan="2">'.
                $this->htmlCanvasTitle($this->canvasTypes['solution']['title'], $this->canvasTypes['solution']['icon']).'</td>'.
				'  <td class="canvas-elt-title" style="width: 10%;" colspan="2">'.
                $this->htmlCanvasTitle($this->canvasTypes['uniquevalue']['title'], $this->canvasTypes['uniquevalue']['icon']).'</td>'.
				'  <td class="canvas-elt-title" style="width: 10%;" colspan="2">'.
                $this->htmlCanvasTitle($this->canvasTypes['unfairadvantage']['title'], $this->canvasTypes['unfairadvantage']['icon']).'</td>'.
				'  <td class="canvas-elt-title" style="width: 10%;" colspan="2">'.
                $this->htmlCanvasTitle($this->canvasTypes['customersegment']['title'], $this->canvasTypes['customersegment']['icon']).'</td>'.
				'</tr>'.
				'<tr>'.
				'  <td class="canvas-elt-box" style="height: 200px;" colspan="2">'.$this->htmlCanvasElements($recordsAry, 'problem').'</td>'.
				'  <td class="canvas-elt-box" style="height: 200px;" colspan="2">'.$this->htmlCanvasElements($recordsAry, 'solution').'</td>'.
				'  <td class="canvas-elt-box" style="height: 200px;" colspan="2">'.$this->htmlCanvasElements($recordsAry, 'uniquevalue').'</td>'.
				'  <td class="canvas-elt-box" style="height: 200px;" colspan="2">'.$this->htmlCanvasElements($recordsAry, 'unfairadvantage').'</td>'.
				'  <td class="canvas-elt-box" style="height: 200px;" colspan="2">'.$this->htmlCanvasElements($recordsAry, 'customersegment').'</td>'.
				'</tr>'.
				'<tr>'.
				'  <td class="canvas-elt-title" style="width: 10%;" colspan="2">'.
                $this->htmlCanvasTitle($this->canvasTypes['alternatives']['title'], $this->canvasTypes['alternatives']['icon']).'</td>'.
				'  <td class="canvas-elt-title" style="width: 10%;" colspan="2">'.
                $this->htmlCanvasTitle($this->canvasTypes['keymetrics']['title'], $this->canvasTypes['keymetrics']['icon']).'</td>'.
				'  <td class="canvas-elt-title" style="width: 10%;" colspan="2">'.
                $this->htmlCanvasTitle($this->canvasTypes['highlevelconcept']['title'], $this->canvasTypes['highlevelconcept']['icon']).'</td>'.
				'  <td class="canvas-elt-title" style="width: 10%;" colspan="2">'.
                $this->htmlCanvasTitle($this->canvasTypes['channels']['title'], $this->canvasTypes['channels']['icon']).'</td>'.
				'  <td class="canvas-elt-title" style="width: 10%;" colspan="2">'.
                $this->htmlCanvasTitle($this->canvasTypes['earlyadopters']['title'], $this->canvasTypes['earlyadopters']['icon']).'</td>'.
				'</tr>'.
				'<tr>'.
				'  <td class="canvas-elt-box" style="height: 200px;" colspan="2">'.$this->htmlCanvasElements($recordsAry, 'alternatives').'</td>'.
				'  <td class="canvas-elt-box" style="height: 200px;" colspan="2">'.$this->htmlCanvasElements($recordsAry, 'keymetrics').'</td>'.
				'  <td class="canvas-elt-box" style="height: 200px;" colspan="2">'.$this->htmlCanvasElements($recordsAry, 'highlevelconcept').'</td>'.
				'  <td class="canvas-elt-box" style="height: 200px;" colspan="2">'.$this->htmlCanvasElements($recordsAry, 'channels').'</td>'.
				'  <td class="canvas-elt-box" style="height: 200px;" colspan="2">'.$this->htmlCanvasElements($recordsAry, 'earlyadopters').'</td>'.
				'</tr>'.
				'<tr>'.
				'  <td class="canvas-elt-title" colspan="5">'.
                $this->htmlCanvasTitle($this->canvasTypes['cost']['title'], $this->canvasTypes['cost']['icon']).'</td>'.
				'  <td class="canvas-elt-title" colspan="5">'.
                $this->htmlCanvasTitle($this->canvasTypes['revenue']['title'], $this->canvasTypes['revenue']['icon']).'</td>'.
				'</tr>'.
				'<tr>'.
				'  <td class="canvas-elt-box" style="height: 200px;" colspan="5">'.
                $this->htmlCanvasElements($recordsAry, 'cost').'</td>'.
				'  <td class="canvas-elt-box" style="height: 200px;" colspan="5">'.
                $this->htmlCanvasElements($recordsAry, 'revenue').'</td>'.
				'</tr>'.
				'</tbody></table>';
			return $html;

        }
        
        /***
         * reportGenerate - Generate report for module
         *
         * @access public
         * @param  int    $id     Canvas identifier
         * @param  string $filter Filter value
         * @return string PDF filename
         */
        public function reportGenerate(int $id, array $filter = []): string
        {

            // Retrieve canvas data
            $leanCanvasRepo = new repositories\leancanvas();
            $leanCanvasAry = $leanCanvasRepo->getSingleCanvas($id);
            !empty($leanCanvasAry) || die("Cannot find canvas with id '$id'");
            $projectId = $leanCanvasAry[0]['projectId'];
            $recordsAry = $leanCanvasRepo->getCanvasItemsById($id);
            $projectsRepo = new repositories\projects();
            $projectAry = $projectsRepo->getProject($projectId);
            !empty($projectAry) || die("Cannot retrieve project id '$projectId'");
            
            // Configuration
            $options = [ 'disclaimer' => $leanCanvasRepo->getDisclaimer() ];
            
            // Generate PDF content
            $pdf = new \YetiForcePDF\Document();
            $pdf->init();
            $pdf->loadHtml($this->htmlReport($projectAry['name'], $leanCanvasAry[0]['title'], $recordsAry, $filter, $options));
            return $pdf->render();

        }
    
    }
}
?>
