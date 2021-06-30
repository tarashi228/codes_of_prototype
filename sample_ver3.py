import cv2
import numpy as np
import datetime

INFILE = "output.mp4"
THRESH = 0.15679398148148
JS_FILE = "sample.js"

ESC_KEY = 27
INTERVAL= 1

class MovieIter(object): #動画のフレームを返すイテレータ
    def __init__(self, moviefile, size=None, inter_method=cv2.INTER_AREA):
        #TODO: check if moviefile exists
        self.org = cv2.VideoCapture(moviefile)
        self.framecnt = 0
        self.size = size #frame size
        self.inter_method = inter_method
    def __iter__(self):
        return self
    def __next__(self):
        self.end_flg, self.frame = self.org.read()
        if not self.end_flg: # end of the movie
            raise StopIteration()
        self.framecnt+=1
        if self.size: # resize when size is specified
            self.frame = cv2.resize(self.frame, self.size, interpolation=self.inter_method)
        return self.frame
    def __del__(self): # anyway it works without destructor 
        self.org.release()

def MSE(pic): # mean square error
    return np.mean(np.square(pic))
    
def MAE(pic): # mean absolute error
    return np.mean(np.abs(pic))

def sec_to_jikoku(sec):
    return str(datetime.timedelta(seconds=sec))

def main():
    picsize = (1, 1)
    chap = 0
    sec = 0

    with open(JS_FILE, "w", encoding="utf-8") as f:
        f.write("$(function() {\n")
    
    frame_cnt = 0
    frame_ultima = np.zeros((*picsize[::-1], 3)) # create empty image
    fps = cv2.VideoCapture(INFILE).get(cv2.CAP_PROP_FPS)
    fps_inv = 1 / fps

    frames = []
    
    for frame in MovieIter(INFILE, None):
        frame_cnt+=1
        if frame_cnt % 10 != 0:
            continue
        frame_penult = frame_ultima
        frame_ultima = cv2.resize(frame, picsize, interpolation=cv2.INTER_AREA) #指定サイズに縮小

        flag = False
        
        cv2.imshow("mov", frame)
        key = cv2.waitKey(1) # quit when esc-key pressed
        if key == ESC_KEY:
            break
        
        #差分画像作成
        diff = frame_ultima.astype(np.int) - frame_penult.astype(np.int)
        
        if MAE(diff)>=THRESH: #閾値よりMAEが大きい場合、カットと判定
            for pre_frame in frames:
                diff = frame_ultima.astype(np.int) - pre_frame.astype(np.int)
                if MAE(diff) < THRESH:
                    flag = True
                    frames.append(frame_penult)
                    break
            
            if flag:
                continue
            
            print("Cut detected!: frame {}".format(frame_cnt))

            if frame_cnt == 10:
                continue

            chap += 1

            pre_sec = round(sec)+1
            sec = round(frame_cnt * fps_inv)

            str_pre_sec = sec_to_jikoku(pre_sec)
            str_sec = sec_to_jikoku(sec)

            with open(JS_FILE, "a", encoding="utf-8") as f:
                f.write("$(\'.chaps\').append(\'<tr class=\"chap\"><td>Chapter" + str(chap) + "<span class=\"text-muted fs-5\">" + str_pre_sec + "</span></td></tr>\');\n")
    chap += 1

    pre_sec = round(sec)+1
    sec = round(frame_cnt * fps_inv)

    str_pre_sec = sec_to_jikoku(pre_sec)
    str_sec = sec_to_jikoku(sec)

    with open(JS_FILE, "a", encoding="utf-8") as f:
        f.write("$(\'.chaps\').append(\'<tr class=\"chap\"><td>Chapter" + str(chap) + "<span class=\"text-muted fs-5\">" + str_pre_sec + "</span></td></tr>\');\n})")
    

if __name__ == "__main__":
    main()