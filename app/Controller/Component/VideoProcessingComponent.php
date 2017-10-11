<?php

/**
 * VideoProcessingComponent class file.
 *
 * @author    Greeshma Radhakrishnan <greeshma@qburst.com>
 * @copyright Copyright &copy; 2013-2014 Patients4Life
 */
App::uses('Component', 'Controller');
App::uses('Vimeo', 'Utility');

/**
 * VideoProcessingComponent to handle videos.
 * 
 * This class is used to handle the processing of vimeo videos.
 * This class is used by the cron shell via API controller to update the 
 * video status and thumbnail of processed videos.
 *
 * @author 		Greeshma Radhakrishnan
 * @package 	Controller.Component
 * @category	Component 
 */
class VideoProcessingComponent extends Component {

    /**
     * Constructor
     * 
     * Initialises the models
     */
    public function __construct() {
        $this->Post = ClassRegistry::init('Post');
        $this->Media = ClassRegistry::init('Media');
        $this->Vimeo = new Vimeo();
    }

    /**
     * Function to update the status of videos.
     * 
     * This function checks if there are any videos which are under 
     * processing status, and gets the thumbnail of those videos.
     * If the transcoding is complete, updates the status of the video as ready
     * and updates the thumbnail url info.
     */
    public function updateVideoStatus() {
        $processingVideos = $this->Media->getProcessingVideos();
        if (!empty($processingVideos)) {
            $count = count($processingVideos);
            echo "Selected {$count} video(s) for checking status.." . PHP_EOL;
            $processedVideos = array();
            foreach ($processingVideos as $mediaId => $videoJSON) {
                $videoDetails = json_decode($videoJSON, true);
                if (isset($videoDetails['video_id'])) {
                    $videoId = $videoDetails['video_id'];
                    echo "VideoId:{$videoId}" . PHP_EOL;
                    $video = $this->Vimeo->getVideoInfo($videoId);
                    if (is_object($video) && (intval($video->is_transcoding) === 0)) {
                        echo 'Video is ready..:)' . PHP_EOL;
                        if (!empty($video->thumbnails)) {
                            $thumbnailUrl = $video->thumbnails->thumbnail[2]->_content;
                            $processedVideo = $videoDetails;
                            $processedVideo['thumbnail_url'] = $thumbnailUrl;
                            $processedVideos[$mediaId] = $processedVideo;
                        }
                    }
                    else
                        echo 'Video is not ready.' . PHP_EOL;
                }
            }

            $this->updateProcessedVideos($processedVideos);
        }
        else
            echo 'No videos are under processing status.' . PHP_EOL;
    }

    /**
     * Function to update processed video details
     * 
     * @param array $processedVideos
     */
    public function updateProcessedVideos($processedVideos) {
        if (!empty($processedVideos)) {
            $processedVideosCount = count($processedVideos);
            echo "{$processedVideosCount} video(s) are processed.." . PHP_EOL;
            $mediaData = array();
            $postVideosData = array();
            foreach ($processedVideos as $mediaId => $videoDetails) {
                $mediaData[] = array(
                    'id' => $mediaId,
                    'content' => json_encode($videoDetails),
                    'status' => Media::STATUS_READY
                );
                $postVideosData[] = array(
                    'media_id' => $mediaId,
                    'video' => $videoDetails
                );
            }

            if ($this->Media->saveAll($mediaData)) {
                echo 'Video status updated in media table.' . PHP_EOL;
                if ($this->Post->updatePostVideosData($postVideosData)) {
                    echo 'Video thumbnail updated in post table.' . PHP_EOL;
                } else {
                    echo 'Failed to update video thumbnail in post table.' . PHP_EOL;
                }
            } else {
                echo 'Failed to update video status in media table.' . PHP_EOL;
            }
        }
    }
}