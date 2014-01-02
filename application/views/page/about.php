<div class="page-header">
  <h2>關於伊爾特系統</h2>
</div>

<h3>起源與展望</h3>
<p>
  伊爾特系統是Ilt System的音譯，Ilt是<strong>I</strong>dentify Tag, user <strong>L</strong>ist, organization <strong>T</strong>ree的縮寫。本系統概念出自於大學學生會網站的會員系統的設計，當初想擴充當時的會員系統的規模，從只有幹部可以申請，擴增到全校同學，並可以有一個完整的權限控制，且針對學校組織變動性高，以及很多時候職位任期只有一年的高流動性，不斷思考該如何編寫一個會員系統取代原本前人用Dreamwear所做的陽春系統，在學習、經驗以及不斷的思考下，伊爾系統終於逐漸成型概念，但是礙于技術一直無法完整實作出一個漂亮的實例，於是在不斷地建了再砍，砍了又建，前前後後重新為這系統重做了五次核心，直到最近自身技術趨於成熟，便藉由推甄需要一份代表作品的機會，最後重置一次本系統，並且有一個可運作的基本雛形，用此作為這兩年在網站程式開發的經驗凝結，並預計將此雛形進行開源，並應用在學生會網站，努力營運並持續修正、改進，甚至導入未來可能就讀研究所所學知識，期許有一天這個系統可以成為人們想要架網站時，一種會員系統核心的選擇。
</p>

<h3>系統基本原理</h3>
<p>
  本系統是由三大資料表類型所組成的系統，這三大類型分別為User(使用者)、Organization（組織）、Identify（身份識別、權限），利用關聯式資料庫透過Identity聯結User和Organization的關係、產生互動，形成一個彈性極高但是聯結緊密的會員系統。並透過這三類資料表的Primary Key擴充更多資料類型，如在User類型在擴充一個Student和Shop的資料，讓會員可以有學生和商家的類型，在Organization增加Files，擴增組織資料甚至增加社團、系學會、學生會的類別，讓整體系統更加豐富，從最簡單的架構透過高彈性增加自己系統的需求。
</p>
<p>
  在資料庫結構上面，User類型的資料表是採用列表的形式，單純記錄一筆一筆的使用者資料，並透過uId去聯結其他屬性，例如本實例中另外增設了有關oauth2資料的儲存。而Organization類型的資料表示採用樹狀資料結構，讓每一個組織節點去記錄其父節點，形成怡個累金字塔型的結構，並透過一些模型，讓我們可以就由此資料結構對組織進行分類，例如各社團的父節點都是名為「社團」的節點，這樣我只要查詢誰的父節點是社團，就可以得到所有社團清單。最後的Identify則是為了聯結前兩者並設有權限所設置，他比較像是Tag（標籤），一種由「一對多(User to Identify)」、「多對一（Identify to Organization）」的聯結，在上面我們記錄著前兩類的Primary Key，讓我們知道哪個使用者是屬於（被標記）為哪個組織的成員，並有欄位設置該使用者在該組織所屬層級，形成一個可延伸成權限系統的資料結構。
</p>

<h3>資料庫結構</h3>
<ul>
  <li>
    user_list
    <pre>
      CREATE TABLE `user_list` (
        `uId` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `uName` varchar(32) NOT NULL DEFAULT '',
        `uStatus` int(11) NOT NULL DEFAULT '0',
        `uCreateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`uId`)
      ) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;      
    </pre>
  </li>
  <li>
    user_oauth
    <pre>
      CREATE TABLE `user_oauth` (
        `uOAuthId` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `uId` int(11) NOT NULL,
        `uOAuthType` int(11) NOT NULL,
        `uOAuthValue` varchar(128) NOT NULL DEFAULT '',
        `uOAuthCreateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`uOAuthId`)
      ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;      
    </pre>
  </li>
  <li>
    organ_tree
    <pre>
      CREATE TABLE `organ_tree` (
        `oId` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `oName` varchar(32) NOT NULL DEFAULT '',
        `oParentId` int(11) NOT NULL,
        `oSortNumber` int(11) NOT NULL DEFAULT '0',
        `oStatus` int(11) NOT NULL DEFAULT '0',
        `oCreateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`oId`)
      ) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;      
    </pre>
  </li>
  <li>
    identify_tag
    <pre>
      CREATE TABLE `identify_tag` (
        `iId` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `uId` int(11) NOT NULL,
        `oId` int(11) NOT NULL,
        `iLevel` int(11) NOT NULL DEFAULT '0',
        `iStatus` int(11) NOT NULL DEFAULT '0',
        `iCreateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`iId`)
      ) ENGINE=InnoDB AUTO_INCREMENT=420 DEFAULT CHARSET=utf8;      
    </pre>
  </li>
</ul>
<p>
</p>

<div class="page-header">
  <h2>開發一個組織生態系的系統──以學校為例 <small>PHPConf2013徵稿錄取文章</small></h2>
</div>

<div>
<ul>
  <li>主題：開發一個組織生態系的系統──以學校為例</li>
  <li>作者：Fntsrlike(若虛)</li>
  <li>單位：國立中興大學學生會18屆資訊長，歷史系四年級學生</li>
  <li>目標：初學者、五年以下開發經驗者</li>
  <li>授權：以 CC BY-NC 3.0 姓名標示-非商業性方式授權。</li>
  <li>內文：
    <div style="margin-left:2em;">
      <p>
        筆者本身是學生會的資訊長，因為將前人的網站核心砍掉重練，利用Framework重製，在編寫的過程中領會到些許開發經驗，所以在這邊與各位分享。由於開發整套系統會利用的技術很多，除了PHP以外，還會用到MySQL、Javascript、JQuery、CSS等，涉及範圍過廣，所以筆者將本篇文章著重在後端的開發上面，尤其是PHP的部分，希望能分享自身的開發經驗給所需的夥伴一點幫助。
      </p>
      <p>
        有鑒於網路上已有許多CMS的程式，以往自身手工開發靜態網頁呈現資料的方式漸漸被取代，現在開發網站多注重於使用者的互動，也就是網路應用程式的興起，而一般資料的顯示多交由CMS幫忙。就一套人群生態系來說，需要的網站服務主要有三大方向：資料呈現與歷史紀錄、使用者互動服務、消息通報系統，而我們可以藉由CMS幫我們完成第一項，例如筆者就是透過MediaWiki去。而第三項我們會利用Facebook粉絲，會有許多社團專頁，並寫一個簡單的週報系統去挑選重要的資訊集結在裡面。最後，第二項就是你想和使用者互動的部分去寫網路應用程式。
      </p>
      <p>
        而在大學裡，我們接觸最大的族群就是學生，要與使用者互動首先就是要建立一套會員系統，而學生又會組織社團，於是我們使用者又會有社團的身分，然後又因各種應用程式與使用者互動會有不同的權限，比如說學權申訴系統只有學生可以申請，且只有學生會學權部的人員可以審理處理，那這個應用程式就會兩種不同的權限需求；又例如海報欄位申請系統，我們為以社團為單位去讓學生申請海報欄位以方便運作，又需要權限讓學生會社團部的人去管理，處理違規事項，於是我們又需要身分與權限。最後，學生會的新聞發布系統，希望所有學生會的幹部都可以使用，那其實我們可以直接設定只要階層在學生會底下的組織即有權限。舉例到此，從這裡我們即可看出在一個組織生態系裡，會以使用者、身分、組織去拼湊出各種權限組合。
      </p>
      <p>
        因此在興大學生會網站，我們便發展出了Ilt系統做為網站核心，Ilt的意思即為 Identify, user-List, organization-Tree的縮寫，正如前段所言以三者所組合建置的權限系統。我們建立使用者相關的資料表、組織樹狀關係資料表、組織階級權限對照表，用這三類資料表去組成這個網站的核心資料庫。而整個網站的重點在於強調使用者互動的網路應用程式，利用程式設計裡「低耦合」的概念，在網站結構上，希望能以Ilt系統作為核心，並將網路應用程式透過擴充的方式建置在網站上，讓應用程式讀取登入中使用者的權限去作出相對的動作與事件。
      </p>
      <p>
        概念大致描寫到上段，接下來講述進行網站建構的具體方式。建立一個複雜性比較高的網站，通常建議引入MVC架構，你可以自行建置或是使用Framework去幫你建置，對於MVC說明的文章已經很多大神寫過了，這邊筆者就不獻醜了。而筆者這邊則是使用CodeIgniter這套輕量級的Framework協助網站的建置。首先我們會將網站的檔案類型在Models, View, Controller的資料夾皆分三類，分別為Ilt系統、網站周邊、網路應用程式，然後在網路應用程式裡又依照網路應用程式做資料夾的分類，讓程式檔案能依照最好釐清的方式放置、方便維護。
      </p>
      <p>
        再來我們就編寫Ilt核心，組織的新增與對應，我們會從根開始寫起，並儲存各單位父元素，形成一個樹狀的組織結構，然後將會員系統、組織樹與權限系統整合起來，權限我們會在Identify的資料表存進使用者在user-list的id、組織在organiztion-tree的id以及他在該組織單位的權限階級，編寫關於此權限資料與資料庫溝通的model，接著並寫出管理介面，讓我們透過網站即可控制權限而不必須直接透過資料庫控制，減少風險，這樣Ilt的核心就算是略有雛型了。
      </p>
      <p>
        在完成Ilt核心後，我們接著開始編寫讓網路應用程式和此Ilt核心溝通的API，讓應用程式可已透過Ilt系統去讀取使用者的登入狀態、資料、紀錄以及該使用者是否有符合應用程式所需要的權限，如果你開放大家使用這套API去撰寫屬於你們組織的網路應用程式，也就是程式不會和核心放在一起，就需要再編寫有關應用程式透過你的Ilt登入後之間的認證傳輸API，讓應用程式要求使用你Ilt系統的資料的權限。當API寫完時，整套系統也大致完成了。
      </p>
      <p>
        筆者利用上述概念建構出學生會的官方網站，並提供學權申訴、海報欄位系統等服務，未來會陸續編寫學生相關服務能夠電子化，成為一個個網路應用程式，並將許多需要紙本記錄容易混亂的資料，轉變透過資料庫幫你存取，方便搜尋及確認。最後將三大方向並行推進，提供學校學生一個良好的服務電子化環境。希望此開發經驗能帶給其他想用PHP開發組織網站的人一些幫助。
    </div>
  </li>  
    </ul>
</div>