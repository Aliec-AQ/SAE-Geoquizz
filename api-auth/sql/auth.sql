CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

DROP TABLE IF EXISTS "users";
CREATE TABLE "public"."users" (
    "id" uuid DEFAULT uuid_generate_v4() NOT NULL,
    "email" character varying(128) NOT NULL,
    "password" character varying(256) NOT NULL,
    "pseudo" character varying(128),
    "role" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "users_email" UNIQUE ("email"),
    CONSTRAINT "users_id" PRIMARY KEY ("id")
) WITH (oids = false);

INSERT INTO "users" ("id", "email", "password", "pseudo", "role") VALUES
('f3b3b3b3-3b3b-3b3b-3b3b-3b3b3b3b3b3b', 'test.test@test.test', '$2y$10$61/PtaYijAEh06ulXcYv8OsH5mFugmCjOCFkuenHSxBnfsGmFi99m', 'testuser', 0),
('118a9bca-b30e-360a-9acb-0f44498fa9cb', 'munoz.theophile@laposte.net', '$2y$10$jdB9eypmLmifN5i5n5IwrOi9G6tySqYhsFAfJhHSgYmldTmuaKd3u', 'theophile', 0),
('bdbc09de-d523-34a5-bec7-743953a7cd2f', 'sgodard@vasseur.com', '$2y$10$w5DrBc0vbyOyZnuTeNuBAuSCDX2NuSrVQAb9w4bN2FD0otn2tazBW', 'sgodard', 0),
('33c59f91-10ff-3e5a-a4dd-ffb5e4d1d513', 'lledoux@dbmail.com', '$2y$10$3jurBwm79Qc0DacDKeYele9UTwRlEd0Aadk.9lrYUw037cgJ91yZm', 'lledoux', 0),
('98c2aeae-a2f1-382c-8c94-65a27d52f991', 'michel.georges@live.com', '$2y$10$mwnKPXFa0d0FB34g1T8SJeV2VcsS7uJNDX3G7BnFvNKSsUV1l8e3i', 'michel', 0),
('f930c1de-5fa2-3832-ba7c-b2d05a9dc2d4', 'rmahe@laposte.net', '$2y$10$YI1njjpuWVSGtavG8zwn.ea06MIh0c1bPebFibyCoSFVjFjRgPxuu', 'rmahe', 0),
('5957675b-b7b0-39ba-8b3a-920b2f7a523f', 'gimenez.alphonse@gregoire.com', '$2y$10$0imxMNucxthxYFG6Xp4IOuO/VDE9Oc56CydZ..GLJRN63xvwmH2ze', 'alphonse', 0),
('fd774f03-935f-39f5-ba95-2f01ffee28dd', 'leon.berger@bailly.org', '$2y$10$zqoHN9o04tK1yve8fDQJMOsxwk8FxHxLtQ0l9XFcrz7ZuLg198wiW', 'leon', 0),
('b5e1f6a4-cc8a-37b4-b23a-3772c3c30cbf', 'lopes.odette@sanchez.net', '$2y$10$0taAHnOfVBWOWd4yLV8HT.oFVAzOylbCV1cQ15aOzhktPgsuvhD12', 'odette', 0),
('9920d017-c728-3859-ab2e-31c4197c10f3', 'louis.arthur@mendes.net', '$2y$10$vHMzzpN2rk896i5WAb/FBektzlCJGt9nGba8OWYFTZk4Y.wVao/f2', 'arthur', 0),
('ad9bfece-6520-3ff1-b497-f897e5bde84a', 'jean89@vidal.fr', '$2y$10$rSC.zrWJTIZRELF1SzdZ/.bCKbpS7q5ZihHtFzGbWkiFFj9QF.Aq2', 'jean', 0),
('438dff6a-4520-3a00-8b2e-16ea002cafef', 'william46@wanadoo.fr', '$2y$10$r1RNVmpPrmVAryjx6dtXZu1tNRU9.TrsFygA.rg7B3Au1ZltzTW4G', 'william', 0),
('751aaf42-f6be-32f3-8a72-14250e710484', 'gturpin@costa.fr', '$2y$10$Y0laIpLvYU14rXFMG2OAV..GO1kM6bsLcIXr0FiOIJp.coJ4PEQa.', 'gturpin', 0),
('d7c22735-2d8a-30bb-bcc0-ab0a2acb56ac', 'henri29@live.com', '$2y$10$uqcrqRJTV9zC7n81Ox1l0ePNEfCTrqedgmMhfqk0sB2WvopOXK0Qy', 'henri', 0),
('aed94255-8dd3-378e-87b5-a1cd2d36eb08', 'jmarin@riviere.com', '$2y$10$CaTTfmPJ5qSkmcERUz8Ul.K4h/W46TjS11ZgS6kSAAD1dCUKNEEnu', 'jmarin', 0),
('ce09855e-6a9c-3a69-bb41-19f444ceedd8', 'wlebrun@yahoo.fr', '$2y$10$fSvn4bnhdiJWg5gVVBcinedWSmYHQVSUMdYSy5Vb2emKJgVQqfxBO', 'wlebrun', 0),
('5e1837b3-3205-333d-80be-1b6617bacc5b', 'vmartin@hotmail.fr', '$2y$10$UcjOqsbIf9eDcQYJKZ2UsuuAPDvm/tni3ANb6C6GE0Zp/Ou6iDGlW', 'vmartin', 0),
('d48232be-5a3d-3aa6-9877-37f27c305f82', 'sophie.lambert@pineau.fr', '$2y$10$ULrTSFMFNu072aOzSYsQFee3IpkBGDSbGJ5mnF3tqJFN/dNqyozqK', 'sophie', 0),
('a70dd64e-3b0e-3175-a5f5-d8012dcd8a73', 'nathalie93@sfr.fr', '$2y$10$0u2nvIoPr3uuWcRMAjX7tedowPkg95TiFnpmbkpHGesG0AuLeInAO', 'nathalie', 0),
('a6ace2ea-3bbf-35ca-bf65-1146caa118b6', 'cecile69@boulanger.com', '$2y$10$SygCY6.efix594JFbNTAdO1/DxYfXJ1Ba0vMHikDeqS2yn22rfQWm', 'cecile', 0),
('74de52df-04ec-3364-bbf4-bf26e191cedb', 'alain15@charrier.fr', '$2y$10$XGRcgAnLSdpU8Qk6bqRcruw25SoE.K66MksGXVCQw3r2lQv8CPEGy', 'alain', 0),
('0c89eb6c-55dc-304a-a568-a682c9c6ceef', 'colette58@orange.fr', '$2y$10$H1pdaffk2W0CdwmJEESsnOpVn1ZTvyAkR4SeJhDOZJEpIo04SpZO2', 'colette', 0),
('11d611ed-fa3e-39e2-b5e0-018b00cef74a', 'gaillard.martin@deschamps.org', '$2y$10$xrj.k0zFDLtmoUqLd/kQruRZ8qQVm9Jqo9zTkgSanLpBsTAJz9R4S', 'martin', 0),
('d785b5be-c84e-3170-adbf-abb636460974', 'matthieu31@hotmail.fr', '$2y$10$AcX8862cJm8ga7ETqN3C6.xC6HCDHK34xcNpImtpe0h1rZzmavMJO', 'matthieu', 0),
('015bf648-9314-31cc-b536-34ad0d043c81', 'lebreton.alfred@dbmail.com', '$2y$10$Rde/u3/PTR2w0fecg5IrkORrD8ji.IYmPlRFQ3/89uFChlUmagnsO', 'alfred', 0),
('a89bd373-a430-3394-8cbc-8ecd24f436e4', 'adelaide66@club-internet.fr', '$2y$10$g2s/5AmqNRpCtCnebHbxne3MztlQ1nUBJfprjUWMuS6M8fgDKsBPW', 'adelaide', 0);
