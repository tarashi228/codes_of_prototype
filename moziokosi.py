import speech_recognition as sr
import MeCab
from sklearn.feature_extraction.text import TfidfVectorizer
import pandas as pd

r = sr.Recognizer()
 
with sr.AudioFile("sample.wav") as source:
    audio = r.record(source)
 
text = r.recognize_google(audio, language='ja-JP')
 
print(text)


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

print(docs)


# モデルを生成
vectorizer = TfidfVectorizer(smooth_idf=False)
X = vectorizer.fit_transform(docs)

# データフレームに表現
values = X.toarray()
feature_names = vectorizer.get_feature_names()
df = pd.DataFrame(values, columns = feature_names,
                  index=["特徴語"])
print(df)
