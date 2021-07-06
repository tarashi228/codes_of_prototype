import ffmpeg
import speech_recognition as sr
import MeCab
from sklearn.feature_extraction.text import TfidfVectorizer
import pandas as pd

# %%
#動画全体の時間を調べる
video_info = ffmpeg.probe("sample.mp4")
duration = float(video_info['streams'][0]['duration'])
time.append(duration)

# %%
# 配列形式で与えられた秒数で動画を切り取り
for i in range(len(time)-1):
    if time[i+1]-time[i]<180:
        stream = ffmpeg.input("sample.mp4")
        stream = ffmpeg.output(stream, "sample"+str(i)+".wav", ss=time[i], t=time[i+1]-time[i])
        ffmpeg.run(stream)
    else:
        t = time[i]
        j = 0
        while time[i+1]-t>180:
            stream = ffmpeg.input("sample.mp4")
            stream = ffmpeg.output(stream, "sample"+str(i)+"_"+str(j)+".wav", ss=t, t=180)
            ffmpeg.run(stream)
            j += 1
            t += 180
        stream = ffmpeg.input("sample.mp4")
        stream = ffmpeg.output(stream, "sample"+str(i)+"_"+str(j)+".wav", ss=t, t=time[i+1]-t)
        ffmpeg.run(stream)
# WAV形式で分割された動画を出力

# %%
# チャプターごとに特徴語を抽出
features = []

for i in range(len(time)-1):
    r = sr.Recognizer()
 
    with sr.AudioFile("sample.wav") as source:
        audio = r.record(source)
 
    text = r.recognize_google(audio, language='ja-JP')


    tokenizer = MeCab.Tagger("-Ochasen")
    tokenizer.parse("")

    def extract(text):
        words = []

        # 単語の特徴リストを生成
        node = tokenizer.parseToNode(text)

        while node:
            # 品詞情報(node.feature)が名詞ならば
            if node.feature.split(",")[0] == u"名詞":
                # 単語(node.surface)をwordsに追加
                words.append(node.surface)
            node = node.next

        # 半角スペース区切りで文字列を結合
        text_result = ' '.join(words)
        return text_result

    docs = []

    text = extract(text)
    docs.append(text)


    # モデルを生成
    vectorizer = TfidfVectorizer(smooth_idf=False)
    X = vectorizer.fit_transform(docs)

    # データフレームに表現
    values = X.toarray()
    feature_names = vectorizer.get_feature_names()
    df = pd.DataFrame(values, columns = feature_names, index=["特徴語"])
    features.append(df.columns[-1])
