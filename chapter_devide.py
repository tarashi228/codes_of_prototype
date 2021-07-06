# バグってる可能性が大いにあります(7/5 15:30)

# 読み込み:mp4ファイル
# 動作:(チャプター分けが検出されるor動画が3分以上になる)で動画を分割
# 出力:分割されたmp4ファイル

# 参考→https://qiita.com/satoshi2nd/items/4f6814b795a772af4af0

import ffmpeg

def chapter_devide('sample.mp4', chapter):

    video = 'sample.mp4'

    video_info = ffmpeg.probe(video)
    duration = float(video_info['streams'][0]['duration']) # 動画の長さ(秒)

    # 動画を分割

    # 動画を分割するために、チャプター分けを行うフレーム数を並べた列を作成
    # チャプター分けは、列 chapter として入力
    # それ以外でも3分以上になったら分割

    i = 0

    # パターン1 まだ最後のチャプター分けじゃない & 次まで3分無い
    # パターン2 まだ最後のチャプター分けじゃない & 次まで3分以上
    # パターン3 最後のチャプター分け & 次まで3分無い
    # パターン4 最後のチャプター分け & 次まで3分以上

    while chapter[i] != None:
        if chapter[i+1] != None:
            if chapter[i+1] - chapter[i] > 180:
                chapter[i+1:i+1] = chapter[i] + 180
        else:
            if duration - chapter[i] > 180:
                chapter[i+1:i+1] = chapter[i] + 180
        i = i + 1
    
    for i in range(0:len(chapter)):
        stream = ffmpeg.input(video, ss=chapter[i], t=chapter[i+1]-chapter[i])
        audio_stream = stream.audio
        stream = ffmpeg.output(stream, audio_stream, 'out+str(i)+.mp4')

chapter_devide('sample.mp4', chapter)