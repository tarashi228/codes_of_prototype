def features(time):
    import ffmpeg
    import speech_recognition as sr
    import MeCab
    from sklearn.feature_extraction.text import TfidfVectorizer
    import pandas as pd
    import os

    # %%
    # 読み込むファイル名を定義
    Fname = "sample.mp4"
    fname = "sample.wav"

    # %%
    #動画全体の時間を調べる
    video_info = ffmpeg.probe(Fname)
    duration = float(video_info['streams'][0]['duration'])
    time.append(duration)

    # %%
    #mp4ファイルをwavファイルに変換
    stream = ffmpeg.input(Fname)
    stream = ffmpeg.output(stream, "sample.wav")
    ffmpeg.run(stream)

    # %%
    # 配列形式で与えられた秒数で動画を切り取り
    chapters = []

    for i in range(len(time)-1):
        if time[i+1]-time[i]<30:
            stream = ffmpeg.input(fname)
            stream = ffmpeg.output(stream, "sample"+str(i)+"_"+str(0)+".wav", ss=time[i], t=time[i+1]-time[i])
            ffmpeg.run(stream)
            chapters.append(0)
        else:
            t = time[i]
            j = 0
            while time[i+1]-t>30:
                stream = ffmpeg.input(fname)
                stream = ffmpeg.output(stream, "sample"+str(i)+"_"+str(j)+".wav", ss=t, t=30)
                ffmpeg.run(stream)
                j += 1
                t += 30
            stream = ffmpeg.input(fname)
            stream = ffmpeg.output(stream, "sample"+str(i)+"_"+str(j)+".wav", ss=t, t=time[i+1]-t)
            ffmpeg.run(stream)
            chapters.append(j)
    os.remove("sample.wav")
    # WAV形式で分割された動画を出力
    # 各チャプターごとに分割動画の数を記録

    # %%
    # チャプターごとに特徴語を抽出
    features = []

    for i in range(len(time)-1):
        for j in range(chapters[i]+1):
            r = sr.Recognizer()

            if j == 0:
                try:
                    with sr.AudioFile("sample"+str(i)+"_"+str(j)+".wav") as source:
                        audio = r.record(source)

                    text = r.recognize_google(audio, language='ja-JP')
                except Exception:
                    pass
            else:
                try:
                    with sr.AudioFile("sample"+str(i)+"_"+str(j)+".wav") as source:
                        audio = r.record(source)

                    text += r.recognize_google(audio, language='ja-JP')
                except Exception:
                    pass

            # 作ったwavファイルを削除
            os.remove("sample"+str(i)+"_"+str(j)+".wav")


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

        if docs != ['']:
            # モデルを生成
            vectorizer = TfidfVectorizer(smooth_idf=False)
            X = vectorizer.fit_transform(docs)

            # データフレームに表現
            values = X.toarray()
            feature_names = vectorizer.get_feature_names()
            df = pd.DataFrame(values, columns = feature_names, index=["特徴語"])
            df.sort_values(by="特徴語", ascending=False, axis=1, inplace=True)
            features.append(df.columns[0])
        else:
            features.append("N/A")

    # %%
    # 得られた特徴語リストを返す
    return(features)